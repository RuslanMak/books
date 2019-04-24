<template>
    <div class="container">

        <!-- create book form -->
        <div v-if="showModalCreadeBook">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-body">
                            <slot name="body">
                                <header class="conf-step__header">
                                    <h2 class="conf-step__title">Добавление книги</h2>
                                </header>
                                <div class="conf-step__wrapper">

                                    <form method="post" v-on:submit="submitDo">
                                    <!--<form method="post" action="/api-save-book">-->
                                        <!--<input type="hidden" name="_token" :value="csrf">-->

                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Выберите писателя</label>
                                            <select class="form-control" id="exampleFormControlSelect1" name="writer" ref="writer">
                                                <option v-for="writerData in writersAll" :value="writerData.id">{{ writerData.name }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Название книги</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="title" ref="title" required>
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
                                                    <input type="number" class="form-control" id="exampleFormControlInput2" step="1" min="1" name="pages" ref="pages" required>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput3">Год выпуска</label>
                                                    <input type="number" class="form-control" id="exampleFormControlInput3" step="1" min="1" name="year" ref="year" required>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput4">Цена</label>
                                                    <input type="number" class="form-control" id="exampleFormControlInput4" step="0.01" min="0" name="price" ref="price" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput5">ISBN</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput5" name="isbn" ref="isbn" required>
                                        </div>

                                        <!--<button class="btn btn-primary" @click.prevent="createBook">Добавить</button>-->
                                        <button class="btn btn-primary" type="submit">Добавить</button>
                                        <button class="btn btn-primary" @click.prevent="showModalCreadeBook=false">Отмена</button>
                                    </form>

                                </div>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END create book form -->

        <button @click="showModalCreadeBook = true" type="button" class="btn btn-primary">Add book</button>

    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                writersAll: [],
                genresAll: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                url: {
                    allWritersData: '/api-all-writers',
                    allGenresData: '/api-all-genres',
                    saveBook: '/api-save-book',
                },
                showModalDeleteBook: false,
                showModalCreadeBook: false,
                createBookData: {},
                have_done: 0,
            }
        },

        mounted() {
            this.update();
        },
        
        methods: {
            update: function () {
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

            createBook: function () {
                console.dir(this.createBookData);
                this.showModalCreadeBook=false;
            },

            submitDo: function (event) {
                event.preventDefault();

                this.createBookData.writer = this.$refs.writer.value;
                this.createBookData.title = this.$refs.title.value;
                this.createBookData.genre = this.$refs.genre.value;
                this.createBookData.pages = this.$refs.pages.value;
                this.createBookData.year = this.$refs.year.value;
                this.createBookData.price = this.$refs.price.value;
                this.createBookData.isbn = this.$refs.isbn.value;

                console.dir(this.createBookData);

                axios.post(this.url.saveBook, this.createBookData)
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
            }


        }
    }
</script>
