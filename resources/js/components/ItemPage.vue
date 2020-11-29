<template>
    <div class="row justify-content-center" v-if="user && item">
        <div class="col-md-6" >
            <div class="card mb-4 box-shadow">
                <img class="card-img-top" :src="item.image">
                <div class="card-body">
                    <h2 class="card-title">{{item.name}}</h2>
                    <p class="card-text">{{item.description}}</p>
                    <div class="align-items-center">
                        <p class="pricing-card-title">$ {{item.price}}</p>
                        <p><small class="text-muted">Active until {{item.active_until}}</small></p>
                        <countdown :time="item.expire_seconds * 1000">
                            <template slot-scope="props">
                                <b>Bid will be closed in: </b>
                                <i>
                                    <span v-if="props.days">{{ props.days }} days,</span>
                                    <span v-if="props.days || props.hours">{{ props.hours }} hours,</span>
                                    <span v-if="props.days || props.hours || props.minutes">{{ props.minutes }} minutes,</span>
                                    <span v-if="props.days || props.hours || props.minutes || props.seconds">{{ props.seconds }} seconds</span>
                                    <span v-else>Bid expired</span>
                                </i>
                                <div v-if="!bidClosed" class="form-check mt-4 text-center">
                                    <p v-if="balanceAvailable">
                                        <button type="button" class="btn btn-outline-secondary" @click="placeBid" @disabled="disableBidButton">
                                            Place bid
                                        </button>
                                    </p>
                                    <div class="alert alert-danger" role="alert" v-else>
                                        Your max bidding setting does not allow you place new bid!
                                    </div>
                                    <p>
                                        <input class="form-check-input" type="checkbox" value="1" id="auto_bid" v-model="autoBidding">
                                        <label class="form-check-label" for="auto_bid">
                                            Enable Auto-bidding
                                        </label>
                                    </p>

                                </div>
                                <div class="alert alert-success" role="alert" v-if="bidPlaced" id="bid_message">
                                    $ {{bidPlaced}} bid was placed
                                </div>
                            </template>
                        </countdown>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="list-group">
                <div v-for="bidItem in bidsHistory" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{bidItem.user_name}} ${{bidItem.amount}}</h5>
                        <small>{{bidItem.created_at}}</small>
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
        props: {
            id: Number,
        },
        data() {
            return {
                item: null,
                autoBidding: false,
                bidsHistory: [],
                bidPlaced: null,
                secondsPassed: null,
                disableBidButton: false
            }
        },
        methods: {
            async getItem() {
                const data = await (this.sendRequest('get', '/api/items/' + this.id));
                if (data) {

                    this.item = data.item;
                    this.bidsHistory = data.bids_history
                    this.autoBidding = data.is_auto
                    if (!this.bidClosed) {
                        setTimeout(() => {
                            this.getItem();
                        }, 5000)
                    }
                }
            },
            async placeBid() {
                this.bidPlaced = null;
                this.disableBidButton = true;
                const data = await (this.sendRequest('post', `/api/items/${this.id}/bid`, {
                    is_auto: this.autoBidding
                }));
                this.disableBidButton = false;
                if (data) {
                    this.item.price = data.last_bid;
                    this.bidPlaced = data.bid_amount;
                    this.bidsHistory = data.bids_history;
                    this.autoBidding = data.is_auto;
                    setTimeout(function () {
                        $('#bid_message').fadeOut(2000)
                    }, 3000)
                }
            }
        },

        computed: {
            bidClosed() {
                return !this.item.expire_seconds;
            },

            balanceAvailable() {
                return this.user.max_amount - this.user.total_bid >= this.item.price
            }
        },

        created() {
            this.getItem()
        }
    }

</script>
