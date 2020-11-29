<template>
    <div v-if="user">
        <div class="row">

            <div class="input-group col-md-3">
                <input v-model="searchKeyword" @keyup.enter="getItems(1)" type="text" class="form-control" placeholder="Put Search keyword..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" @click="getItems(1)">Search</button>
                </div>
            </div>
            <div class="input-group col-md-3">
                <button type="button" class="btn btn-primary" v-if="sort==1" @click="changeSort()">Displayed price: low to high</button>
                <button type="button" class="btn btn-danger" v-if="sort==-1" @click="changeSort()">Displayed price: high to low</button>
            </div>

        </div>
        <template v-if="items.length">
            <div class="row mt-4">
                <div class="col-md-3" v-for="item in items" :key="'item_' + item.id">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" :src="item.image">
                        <div class="card-body">
                            <h4 class="card-title">{{item.name}}</h4>
                            <p class="card-text">{{item.description}}</p>
                            <div class="align-items-center">
                                <p class="pricing-card-title">$ {{item.price}}</p>
                                <p><small class="text-muted">Active until {{item.active_until}}</small></p>
                                <p>
                                    <a :href="'/items/' + item.id">
                                        <button type="button" class="btn btn-sm btn-outline-secondary">bid now</button>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <pagination :data="responseData" v-on:pagination-change-page="getItems"></pagination>
            </div>
        </template>
    </div>


</template>

<script>
    import auth from '../mixins/auth';

    export default {
        mixins: [auth],
        data() {
            return {
                sort: 1,
                items: [],
                error: {},
                token: localStorage.token,
                responseData: {
                },
                searchKeyword: ''
            }
        },
        methods: {

            changeSort() {
                this.sort *= -1;
                let page = this.responseData.page ? this.responseData.page : 1;
                this.getItems(page)
            },

            async getItems(page) {
                if (!page) {
                    page = 1;
                }
                const data = await (this.sendRequest('post', '/api/items', {
                    page,
                    sort: this.sort,
                    filter: this.searchKeyword
                }));
                if (data) {
                    this.items = data.data;
                    this.responseData = data
                    console.log(data)
                }
            }
        },
        created() {
            this.getItems()
        }
    }

</script>
