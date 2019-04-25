<template>
    <div class="container">

        <!-- create book form -->
        <div v-if="showModalCreade">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-body">
                            <slot name="body">
                                <header class="conf-step__header">
                                    <h2 class="conf-step__title">Добавление писателя</h2>
                                </header>
                                <div class="conf-step__wrapper">

                                    <form method="post" v-on:submit="submitDo">
                                    <!--<form method="post" action="/api-save-writer">-->
                                        <!--<input type="hidden" name="_token" :value="csrf">-->

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">ФИО</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="name" ref="name" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput3">Год рождения</label>
                                            <input type="number" class="form-control" id="exampleFormControlInput3" step="1" min="1" name="birthday" ref="birthday" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlSelect2">Тематика</label>
                                            <select multiple class="form-control" id="exampleFormControlSelect2" name="genre" v-model="selected">
                                                <option v-for="genreData in genresAll" :value="genreData.id">{{ genreData.genre }}</option>
                                            </select>
                                        </div>

                                        <button class="btn btn-primary" type="submit">Добавить</button>
                                        <button class="btn btn-primary" @click.prevent="showModalCreade=false">Отмена</button>
                                    </form>

                                </div>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END create book form -->

        <button @click="showModalCreade = true" type="button" class="btn btn-primary">Add writer</button>

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
                    allGenresData: '/api-all-genres',
                    saveWriter: '/api-save-writer',
                },
                showModalCreade: false,
                createWriterData: {},
                have_done: 0,
                selected: [1]
            }
        },

        mounted() {
            this.update();
        },

        // watch: {
        //     genre: function() {
        //         console.dir(this.genre);
        //     }
        // },
        
        methods: {
            update: function () {

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
                // console.dir(this.$refs.genre.length);

                let genres = this.selected.join();
                // console.log(genres);

                this.createWriterData.name = this.$refs.name.value;
                this.createWriterData.birthday = this.$refs.birthday.value;
                this.createWriterData.genre = genres;

                // console.dir(this.createWriterData);

                axios.post(this.url.saveWriter, this.createWriterData)
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
            }


        }
    }
</script>
