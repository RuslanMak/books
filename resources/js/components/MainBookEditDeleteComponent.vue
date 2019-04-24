<template>
    <div class="container">

        <!-- edit-delete book form -->
        <div v-if="showModalCreadeBook">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-body">
                            <slot name="body">
                                <header class="conf-step__header">
                                    <h2 class="conf-step__title">Изменить/Удалить книгу</h2>
                                </header>
                                <div class="conf-step__wrapper">

                                    <form method="post" v-on:submit="submitDo">
                                    <!--<form method="post" action="/api-save-book">-->
                                        <!--<input type="hidden" name="_token" :value="csrf">-->

                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Писатель</label>
                                            <select class="form-control" id="exampleFormControlSelect1" name="writer" ref="writer">
                                                <option v-for="writerData in writersAll" :value="writerData.id">{{ writerData.name }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Название книги</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="title" :value="bookDataArr.title" ref="title" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect2">Тема</label>
                                            <select class="form-control" id="exampleFormControlSelect2" name="genre" ref="genre">
                                                <option v-for="genreData in genresAll" :value="genreData.id">{{ genreData.genre }}</option>
                                            </select>
                                        </div>

                                        <div class="form-row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput2">Количество страниц</label>
                                                    <input type="number" class="form-control" id="exampleFormControlInput2" step="1" min="1" name="pages" :value="bookDataArr.pages" ref="pages" required>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput3">Год выпуска</label>
                                                    <input type="number" class="form-control" id="exampleFormControlInput3" step="1" min="1" name="year" :value="bookDataArr.year" ref="year" required>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput4">Цена</label>
                                                    <input type="number" class="form-control" id="exampleFormControlInput4" step="0.01" min="0" name="price" :value="bookDataArr.price" ref="price" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput5">ISBN</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput5" name="isbn" :value="bookDataArr.isbn" ref="isbn" required>
                                        </div>

                                        <button class="btn btn-primary" type="submit">Изменить</button>
                                        <button class="btn btn-primary" @click.prevent="deleteBookFunc">Удалить</button>
                                        <button class="btn btn-primary" @click.prevent="cancelFn">Отмена</button>
                                    </form>

                                </div>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END edit-delete book form -->

        <!--<button @click="showModalCreadeBook = true" type="button" class="btn btn-primary">Edit delete</button>-->

    </div>
</template>

<script>
    export default {
        props: [
            'book_id'
        ],
        data: function() {
            return {
                bookDataArr: [],
                writersAll: [],
                genresAll: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                url: {
                    allWritersData: '/api-all-writers',
                    allGenresData: '/api-all-genres',
                    saveBook: '/api-save-book',
                    bookData: '/api-book/',
                    deleteBook: '/api-book-delete/',
                    updateBook: '/api-book-update/',
                },
                showModalDeleteBook: false,
                showModalCreadeBook: false,
                updateBookData: {},
                have_done: 0,
            }
        },

        mounted() {
            if (this.book_id) {
                this.showModalCreadeBook = true;
                // console.log(this.book_id);
                this.update();
            }
        },
        
        methods: {
            update: function () {
                axios.get(this.url.bookData + this.book_id)
                    .then((response) => {
                        this.bookDataArr = response.data;
                        console.dir(this.bookDataArr);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });

                axios.get(this.url.allWritersData)
                    .then((response) => {
                        this.writersAll = response.data;
                        // console.dir(this.writersAll);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });

                axios.get(this.url.allGenresData)
                    .then((response) => {
                        this.genresAll = response.data;
                        // console.dir(this.genresAll);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
            },

            submitDo: function (event) {
                event.preventDefault();

                this.updateBookData.writer = this.$refs.writer.value;
                this.updateBookData.title = this.$refs.title.value;
                this.updateBookData.genre = this.$refs.genre.value;
                this.updateBookData.pages = this.$refs.pages.value;
                this.updateBookData.year = this.$refs.year.value;
                this.updateBookData.price = this.$refs.price.value;
                this.updateBookData.isbn = this.$refs.isbn.value;

                console.dir(this.updateBookData);

                axios.post(this.url.updateBook + this.book_id, this.updateBookData)
                    .then(response => {
                        console.log(response);
                        this.update();
                    })
                    .catch(error => {
                        console.log(error.response);
                    });

                this.onChange();
                this.showModalCreadeBook=false;

            },

            //запуск родительской ф-ции
            onChange () {
                this.$emit('update')
            },

            deleteBookFunc: function () {
                axios.delete(this.url.deleteBook + this.book_id)
                    .then(response => {
                        console.log(response);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });

                this.showModalCreadeBook = false;
                this.onChange();
            },

            cancelFn: function () {
                this.showModalCreadeBook = false;
                this.onChange();
            }


        }
    }
</script>
