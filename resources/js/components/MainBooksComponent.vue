<template>
    <div class="container">

        <div class="row justify-content-center">

            <table class="table table-bordered table-hover" style="background-color: #fff;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Writer</th>
                        <th scope="col">Title</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Pages</th>
                        <th scope="col">Year</th>
                        <th scope="col">Price</th>
                        <th scope="col">ISBN</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="book in dataAll"  v-on:click="selectedBook(book.id)">
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
                takeInApi: 100,
                url: {
                    allBooksData: '/api-all-books/',
                },
                createData: {},
                selectedBookId: null,
            }
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
            }

        }
    }
</script>
