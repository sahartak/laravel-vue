<template>
    <form action="/action_page.php" @submit.prevent="submit" v-if="user">
        <div class="alert alert-success" role="alert" v-if="updated">
            Settings are updated
        </div>
        <div>
            <i>You already have bid in total ${{user.total_bid}}</i>
        </div>
        <div class="form-group">
            <label for="max_bid">Max bid amount:</label>
            <input @input="resetUpdated" type="number" min="1" max="1000000" class="form-control" id="max_bid" v-model="maxAmount">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</template>

<script>
    import auth from '../mixins/auth';
    export default {
        mixins: [auth],
        data() {
            return {
                maxAmount: 0,
                updated: false
            }
        },
        methods: {
            async submit() {
                this.resetUpdated()
                const result = await (this.sendRequest('put', '/api/settings', {
                    maxAmount: this.maxAmount
                }))
                console.log(result)
                if (result) {
                    this.updated = true;
                }
            },

            resetUpdated() {
                this.updated = false;
            }
        },

        watch: {
            user() {
                this.maxAmount = this.user ? this.user.max_amount : 0
            }
        }


    }
</script>
