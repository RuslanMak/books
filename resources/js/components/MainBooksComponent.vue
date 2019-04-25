<template>
    <div class="container">

        <div class="row justify-content-center">
            <h2>Общая сума книг на сайте: {{ totalItems }}</h2>
        </div>

        <div class="row justify-content-center">
            <h3>Выбирете количество книжек на странице: </h3>
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
                        <th scope="col">Автор</th>
                        <th scope="col">Название</th>
                        <th scope="col">Тема</th>
                        <th scope="col">Кол-во страниц</th>
                        <th scope="col">Год выпуска</th>
                        <th scope="col">Стоимость</th>
                        <th scope="col">ISBN</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th scope="row">Фильтр <button @click="cleanFilterObj" type="button" class="btn btn-secondary btn-sm">Clean</button></th>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.name"></td>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.title"></td>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.genre"></td>
                        <td>
                            <input v-on:change="findFun" type="text" class="form-control" v-model="filter.pages_min" placeholder="min">
                            <input v-on:change="findFun" type="text" class="form-control" name="pages" v-model="filter.pages_max" placeholder="max">
                        </td>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.year"></td>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.price"></td>
                        <td><input v-on:change="findFun" type="text" class="form-control" v-model="filter.isbn"></td>
                    </tr>
                    <tr v-for="book in dataAll"  v-on:click="selectedBook(book.id)" style="cursor: pointer;">
                        <th scope="row">{{ book.id }}</th>
                        <td>{{ book.name }}</td>
                        <td>{{ book.title }}</td>
                        <td>{{ book.genre }}</td>
                        <td>{{ book.pages }}</td>
                        <td>{{ book.year }}</td>
                        <td>{{ book.price }}</td>
                        <td>{{ book.isbn }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <main-books-add-component @update="onStep1Update"></main-books-add-component>
        <!-- @update="update" -> при обновлении данных вызываем метод update-->
        <main-book-edit-delete-component v-if="selectedBookId" :book_id="selectedBookId" @update="update"></main-book-edit-delete-component>

    </div>
</template>

<script>
    export default {
        props: [
            //get data from Blade
            'totalItems'
        ],
        data: function() {
            return {
                is_refresh: false,
                dataAll: [],
                skipInApi: 0,
                takeInApi: 3,
                url: {
                    allBooksData: '/api-all-books/',
                    filteredBooksData: '/api-filtered-books/',
                },
                createData: {},
                selectedBookId: null,
                filter: {
                    'name': '',
                    'title': '',
                    'genre': '',
                    'pages_min': '',
                    'pages_max': '',
                    'year': '',
                    'price': '',
                    'isbn': '',
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
            this.update();
        },
        
        methods: {
            update: function () {
                this.is_refresh = true;
                this.selectedBookId = null;

                axios.get(this.url.allBooksData + this.skipInApi + '/' + this.takeInApi)
                    .then((response) => {
                        this.dataAll = response.data;
                        this.is_refresh = false;
                        // console.dir(this.dataAll);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
            },

            onStep1Update() {
                // console.log('cool');
                this.update();
            },

            selectedBook: function (id) {
                // console.log(id);
                this.selectedBookId = id;
            },

            findFun: function () {
                //сборка get запроса
                let getForFilter = '?';
                if (this.filter.name !== '') {
                    getForFilter += '&name=' + this.filter.name;
                }
                if (this.filter.title !== '') {
                    getForFilter += '&title=' + this.filter.title;
                }
                if (this.filter.genre !== '') {
                    getForFilter += '&genre=' + this.filter.genre;
                }
                if (this.filter.pages_min !== '') {
                    getForFilter += '&pages_min=' + this.filter.pages_min;
                }
                if (this.filter.pages_max !== '') {
                    getForFilter += '&pages_max=' + this.filter.pages_max;
                }
                if (this.filter.year !== '') {
                    getForFilter += '&year=' + this.filter.year;
                }
                if (this.filter.price !== '') {
                    getForFilter += '&price=' + this.filter.price;
                }
                if (this.filter.isbn !== '') {
                    getForFilter += '&isbn=' + this.filter.isbn;
                }

                // console.log(getForFilter);

                axios.get(this.url.filteredBooksData + this.skipInApi + '/' + this.takeInApi + '/' + getForFilter)
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
                this.filter.title='';
                this.filter.genre='';
                this.filter.pages_min='';
                this.filter.pages_max='';
                this.filter.year='';
                this.filter.price='';
                this.filter.isbn='';

                this.pageNumber = 1;
                this.skipInApi = 0;
                this.update();
            }

        },

        computed:{
            pageCount(){
                let l = this.totalItems,
                    s = this.takeInApi;
                // console.log(l);
                this.maxPages = Math.ceil(l/s);
                // console.log(this.maxPages);
                return this.maxPages;
            },
        },
    }
</script>
