<template>
    <div class="row" v-if="items.length">
        <div class="col-md-4" v-for="item in items" :key="'item_' + item.id">
            <div class="card mb-4 box-shadow">
                <img class="card-img-top" :src="item.image">
                <div class="card-body">
                    <p class="card-title">{{item.name}}</p>
                    <p class="card-text">{{item.description}}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="pricing-card-title">$ {{item.price}}</p>
                        <small class="text-muted">Active until {{item.active_until}}</small>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary">bid now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import auth from '../mixins/auth';
    export default {
        mixins: [auth],
        data() {
            return {
                items: [],
                error: {},
                token: localStorage.token,
                pagnaton:[[[[[[]]]]]]
                page:1,
                totalPages:1
            }
        },
        methods: {
           async getItems() {
               const data = await (this.sendRequest('get', '/api/items'));
               if (data) {
                   this.items = data.data;
                   this.totalPages = data.last_page;
                   console.log(data)
               }
           }
        },
        created() {
            this.getItems()
        }
    }

</script>