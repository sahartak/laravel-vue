<template>
    <form method="POST" @submit.prevent="submit">
        <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" v-model="fields.email" name="email" value="" required autocomplete="email" autofocus>
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" v-model="fields.password" name="password" required autocomplete="current-password">
            </div>
        </div>

        <div v-if="error && error.errors" class="text-danger">{{ error.errors[0] }}</div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </div>
        </div>
    </form>
</template>

<script>
    import auth from '../mixins/auth';
    export default {
        mixins: [auth],
        data() {
            return {
                fields: {},
                error: {},
                token: '',
                loaded: true,
            }
        },
        methods: {
            submit() {

                if (this.loaded) {
                    this.loaded = false;
                    this.success = false;
                    this.error = {};
                    axios.post('/api/login', this.fields).then(response => {
                        this.fields = {};
                        this.loaded = true;
                        this.token = response.data.token;
                        localStorage.token = this.token;
                        window.location.href = '/items';
                        //this.$router.push('/api/items');
                    }).catch(error => {
                        this.loaded = true;
                        if (error.response.status === 401) {
                            this.error = error.response.data || {};
                        }
                    });
                }
            },
        },


    }
</script>
