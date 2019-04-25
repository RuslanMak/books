<template>
    <div class="container">

        <!-- edit-delete form -->
        <div v-if="showModalCreade">
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
                                            <label for="exampleFormControlInput1">ФИО</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="name" :value="writerDataArr.name" ref="name" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput3">Год рождения</label>
                                            <input type="number" class="form-control" id="exampleFormControlInput3" step="1" min="1" name="birthday" :value="writerDataArr.birthday" ref="birthday" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlSelect2">Тематика</label>
                                            <select multiple class="form-control" id="exampleFormControlSelect2" name="genre" v-model="selected">
                                                <option v-for="genreData in genresAll" :value="genreData.id">{{ genreData.genre }}</option>
                                            </select>
                                        </div>

                                        <!--<span>Выбрано: {{ selected }}</span>-->
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
        <!-- END edit-delete form -->

    </div>
</template>

<script>
    export default {
        props: [
            'writer_id'
        ],
        data: function() {
            return {
                writerDataArr: [],
                writersAll: [],
                genresAll: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                url: {
                    writerData: '/api-writer/',
                    allGenresData: '/api-all-genres',
                    saveBook: '/api-save-book',
                    deleteBook: '/api-book-delete/',
                    updateWriter: '/api-writer-update/',
                },
                showModalCreade: false,
                updateWriterData: {},
                have_done: 0,
                selected: []
            }
        },

        mounted() {
            if (this.writer_id) {
                this.showModalCreade = true;
                // console.log(this.writer_id);
                this.update();
            }
        },
        
        methods: {
            update: function () {
                axios.get(this.url.writerData + this.writer_id)
                    .then((response) => {
                        this.writerDataArr = response.data;
                        // console.dir(this.writerDataArr);
                        this.selected = this.writerDataArr.id_genres.split(',');
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

                let genres = this.selected.join();
                // console.log(genres);

                this.updateWriterData.name = this.$refs.name.value;
                this.updateWriterData.birthday = this.$refs.birthday.value;
                this.updateWriterData.genre = genres;

                // console.dir(this.updateWriterData);

                axios.post(this.url.updateWriter + this.writer_id, this.updateWriterData)
                    .then(response => {
                        console.log(response);
                        this.update();
                    })
                    .catch(error => {
                        console.log(error.response);
                    });

                this.onChange();
                this.showModalCreade=false;

            },

            //запуск родительской ф-ции
            onChange () {
                this.$emit('update')
            },

            deleteBookFunc: function () {
                axios.delete(this.url.deleteBook + this.writer_id)
                    .then(response => {
                        console.log(response);
                    })
                    .catch(error => {
                        console.log(error.response)
                    });

                this.showModalCreade = false;
                this.onChange();
            },

            cancelFn: function () {
                this.showModalCreade = false;
                this.onChange();
            }


        }
    }
</script>
