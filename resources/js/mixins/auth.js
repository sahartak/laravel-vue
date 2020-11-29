export default {

    data() {
        return {
            token: localStorage.token,
            user: null
        }
    },

    methods: {



        async getUser() {
            if (this.token) {
                await (this.sendRequest('get', '/api/account'));
            }
        },

        async sendRequest(method, url, params) {
            const authHeader = {
                headers: {
                    Authorization: 'Bearer ' + this.token
                }
            };
            let request;

            if (method === 'get') {
                request = axios.get(url, authHeader);
            } else {
                request = axios[method](url, params, authHeader);
            }

            const result = await request.catch(error => {
                if (error.response.status === 401) {
                    if (window.location.pathname != '/') {
                        window.location.href = '/';
                    }
                } else {
                    if (error.response.data && error.response.data.error) {
                        error = error.response.data.error
                    }
                    alert(error);
                }
            });
            if (result && result.data) {
                this.user = result.data.user;
                if (!this.user) {
                    localStorage.removeItem('token');
                } else if (window.location.pathname == '/') {
                    window.location.href = '/items';
                }


                return result.data.data;
            }
        }
    },

    mounted() {
        this.getUser();
    }

}
