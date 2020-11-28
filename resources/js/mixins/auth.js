export default {

    data() {
        return {
            user: null
        }
    },

    methods: {
        async sendRequest(method, url, params){
            const authHeader = {
                headers: {
                    Authorization: 'Bearer ' + this.token
                }
            };
            let request;

            if (method === 'get') {
                request = axios.get(url, authHeader);
            } else {
                request = axios[method](url,params, authHeader);
            }

            const result = await request.catch(error => {
                if (error.response.status === 401) {
                    window.location.href = '/';
                }
            });
            if (result.data) {
                this.user = result.data.user;
                return result.data.data;
            }
        }
    }

}