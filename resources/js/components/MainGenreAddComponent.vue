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
                                    <h2 class="conf-step__title">Добавление тематику</h2>
                                </header>
                                <div class="conf-step__wrapper">

                                    <form method="post" v-on:submit="submitDo">
                                    <!--<form method="post" action="/api-save-genres">-->
                                        <!--<input type="hidden" name="_token" :value="csrf">-->

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Тематика</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="genre" v-model="createGenresData.genre" required>
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

        <button @click="showModalCreade = true" type="button" class="btn btn-primary">Добавать тематику</button>

    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                // csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                url: {
                    saveGenres: '/api-save-genres',
                },
                showModalCreade: false,
                createGenresData: {},
                have_done: 0
            }
        },

        mounted() {

        },
        
        methods: {
            update: function () {

            },

            submitDo: function (event) {
                event.preventDefault();

                axios.post(this.url.saveGenres, this.createGenresData)
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
