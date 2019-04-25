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
                                            <label for="exampleFormControlInput1">Тематика</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="genre" :value="genreDataArr.genre" ref="genre" required>
                                        </div>

                                        <!--<span>Выбрано: {{ selected }}</span>-->
                                        <button class="btn btn-primary" type="submit">Изменить</button>
                                        <button class="btn btn-primary" @click.prevent="deleteGenreFunc">Удалить</button>
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
                genreDataArr: [],
                writersAll: [],
                genresAll: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                url: {
                    genreData: '/api-genre/',
                    deleteGenre: '/api-genre-delete/',
                    updateGenre: '/api-genre-update/',
                },
                showModalCreade: false,
                updateGenreData: {},
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
                console.log(this.writer_id);
                
                axios.get(this.url.genreData + this.writer_id)
                    .then((response) => {
                        this.genreDataArr = response.data;
                        // console.dir(this.genreDataArr);
                        this.selected = this.genreDataArr.id_genres.split(',');
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
            },

            submitDo: function (event) {
                event.preventDefault();

                this.updateGenreData.genre = this.$refs.genre.value;

                console.dir(this.updateGenreData);

                axios.post(this.url.updateGenre + this.writer_id, this.updateGenreData)
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

            deleteGenreFunc: function () {
                axios.delete(this.url.deleteGenre + this.writer_id)
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
