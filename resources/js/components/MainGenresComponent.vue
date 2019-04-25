<template>
    <div class="container">

        <div class="row justify-content-center">
            <h2>Количество тематик на сайте: {{ this.totalGenres }}</h2>
        </div>

        <div class="row justify-content-center">
            <h3>Выбирете количество тематик на странице: </h3>
            <button type="button" class="btn btn-secondary btn-lg" disabled>{{ takeInApi }}</button>
            <button v-if="takeInApi !== 2" @click="takeInApi = 2" type="button" class="btn btn-outline-dark">2</button>
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
                        <th scope="col">Тематика</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th scope="row">Фильтр <button @click="cleanFilterObj" type="button" class="btn btn-secondary btn-sm">Clean</button></th>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.genre"></td>
                    </tr>
                    <tr v-for="genre in dataAll"  v-on:click="selectedGenre(genre.id)" style="cursor: pointer;">
                        <th scope="row">{{ genre.id }}</th>
                        <td>{{ genre.genre }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <main-genre-add-component @update="onStep1Update"></main-genre-add-component>
        <!-- @update="update" -> при обновлении данных вызываем метод update-->
        <main-genre-edit-delete-component v-if="selectedGenreId" :writer_id="selectedGenreId" @update="update"></main-genre-edit-delete-component>

    </div>
</template>

<script>
    export default {
        props: [
            //get data from Blade
            'totalGenres'
        ],
        data: function() {
            return {
                is_refresh: false,
                dataAll: [],
                skipInApi: 0,
                takeInApi: 10,
                url: {
                    allGenresData: '/api-all-genres/',
                    filteredGenresData: '/api-filtered-genres/',
                },
                selectedGenreId: null,
                filter: {
                    'genre': '',
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
            console.log(this.totalGenres);
            this.update();
        },
        
        methods: {
            update: function () {
                this.is_refresh = true;
                this.selectedGenreId = null;

                axios.get(this.url.allGenresData + this.skipInApi + '/' + this.takeInApi)
                    .then((response) => {
                        this.dataAll = response.data;
                        this.is_refresh = false;
                        console.dir(this.dataAll);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
            },

            onStep1Update() {
                // console.log('cool');
                this.update();
            },

            selectedGenre: function (id) {
                // console.log(id);
                this.selectedGenreId = id;
            },

            findFun: function () {
                //сборка get запроса
                let getForFilter = '?';
                if (this.filter.genre !== '') {
                    getForFilter += '&genre=' + this.filter.genre;
                }

                console.log(getForFilter);

                axios.get(this.url.filteredGenresData + this.skipInApi + '/' + this.takeInApi + '/' + getForFilter)
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
                this.filter.genre='';

                this.pageNumber = 1;
                this.skipInApi = 0;
                this.update();
            },

        },

        computed:{
            pageCount(){
                let l = this.totalGenres,
                    s = this.takeInApi;
                // console.log(l);
                this.maxPages = Math.ceil(l/s);
                // console.log(this.maxPages);
                return this.maxPages;
            },
        },
    }
</script>
