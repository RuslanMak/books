<template>
    <div class="container">

        <div class="row justify-content-center">
            <h2>Количество писателей на сайте: {{ this.totalWriters }}</h2>
        </div>

        <div class="row justify-content-center">
            <h3>Выбирете количество писателей на странице: </h3>
            <button type="button" class="btn btn-secondary btn-lg" disabled>{{ takeInApi }}</button>
            <button v-if="takeInApi !== 10" @click="takeInApi = 10" type="button" class="btn btn-outline-dark">10</button>
            <button v-if="takeInApi !== 20" @click="takeInApi = 20" type="button" class="btn btn-outline-dark">20</button>
            <button v-if="takeInApi !== 50" @click="takeInApi = 50" type="button" class="btn btn-outline-dark">50</button>
            <button v-if="takeInApi !== 100" @click="takeInApi = 100" type="button" class="btn btn-outline-dark">100</button>
        </div>

        <div class="row justify-content-center" style="margin-bottom: 15px">
            <button v-if="pageNumber !== 1" @click="prevPage" type="button" class="btn btn-outline-dark"><-prev</button>
            <button type="button" class="btn btn-secondary btn-lg" disabled>{{ pageNumber }}</button>
            <button v-if="pageNumber <= pageCount -1" @click="nextPage" type="button" class="btn btn-outline-dark">next-></button>
        </div>

        <div class="row justify-content-center">

            <table class="table table-bordered table-hover" style="background-color: #fff;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Автор</th>
                        <th scope="col">Год рождения</th>
                        <th scope="col">Тематика</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th scope="row">Фильтр <button @click="cleanFilterObj" type="button" class="btn btn-secondary btn-sm">Clean</button></th>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.name"></td>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.birthday"></td>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.id_genres"></td>
                    </tr>
                    <tr v-for="writer in dataAll"  v-on:click="selectedWriter(writer.id)" style="cursor: pointer;">
                        <th scope="row">{{ writer.id }}</th>
                        <td>{{ writer.name }}</td>
                        <td>{{ writer.birthday }}</td>
                        <td>{{ genresFun(writer.id_genres) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <main-writer-add-component @update="onStep1Update"></main-writer-add-component>
        <!-- @update="update" -> при обновлении данных вызываем метод update-->
        <main-writer-edit-delete-component v-if="selectedWriterId" :writer_id="selectedWriterId" @update="update"></main-writer-edit-delete-component>

    </div>
</template>

<script>
    export default {
        props: [
            //get data from Blade
            'totalWriters'
        ],
        data: function() {
            return {
                is_refresh: false,
                dataAll: [],
                genresAll: [],
                skipInApi: 0,
                takeInApi: 10,
                url: {
                    allGenresData: '/api-all-genres',
                    allWritersData: '/api-all-writers/',
                    filteredWritersData: '/api-filtered-writers/',
                },
                createData: {},
                selectedWriterId: null,
                filter: {
                    'name': '',
                    'birthday': '',
                    'id_genres': '',
                },
                pageNumber: 1,
                maxPages: 0,
            }
        },

        watch: {
            takeInApi: function () {
                this.update();
                this.cleanFilterObj();
            },
        },

        mounted() {
            console.log(this.totalWriters);
            this.update();
        },
        
        methods: {
            update: function () {
                this.is_refresh = true;
                this.selectedWriterId = null;

                axios.get(this.url.allWritersData + this.skipInApi + '/' + this.takeInApi)
                    .then((response) => {
                        this.dataAll = response.data;
                        this.is_refresh = false;
                        console.dir(this.dataAll);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });

                axios.get(this.url.allGenresData)
                    .then((response) => {
                        this.genresAll = response.data;
                        console.dir(this.genresAll);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
            },

            onStep1Update() {
                // console.log('cool');
                this.update();
            },

            selectedWriter: function (id) {
                // console.log(id);
                this.selectedWriterId = id;
            },

            findFun: function () {
                //сборка get запроса
                let getForFilter = '?';
                if (this.filter.name !== '') {
                    getForFilter += '&name=' + this.filter.name;
                }
                if (this.filter.birthday !== '') {
                    getForFilter += '&birthday=' + this.filter.birthday;
                }
                if (this.filter.id_genres !== '') {
                    getForFilter += '&id_genres=' + this.filter.id_genres;
                }

                console.log(getForFilter);

                axios.get(this.url.filteredWritersData + this.skipInApi + '/' + this.takeInApi + '/' + getForFilter)
                    .then((response) => {
                        this.dataAll = response.data;
                        this.is_refresh = false;
                        // console.dir(this.dataAll);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
            },

            nextPage(){
                this.pageNumber++;
                this.skipInApi += this.takeInApi;
                this.update();
            },
            prevPage(){
                this.pageNumber--;
                this.skipInApi -= this.takeInApi;
                this.update();
            },

            cleanFilterObj: function () {
                this.filter.name='';
                this.filter.birthday='';
                this.filter.id_genres='';

                this.pageNumber = 1;
                this.skipInApi = 0;
                this.update();
            },

            genresFun: function (ids) {
                let idsArr = ids.split(',');
                let nameArr = [];

                idsArr.forEach(date => {
                    let genres = this.genresAll.filter(x => x["id"] == date);

                    if(genres[0]) {
                        nameArr.push(genres[0].genre);
                    }
                });

                return nameArr.join(', ');
            }

        },

        computed:{
            pageCount(){
                let l = this.totalWriters,
                    s = this.takeInApi;
                // console.log(l);
                this.maxPages = Math.ceil(l/s);
                // console.log(this.maxPages);
                return this.maxPages;
            },
        },
    }
</script>
