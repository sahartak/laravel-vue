<template>

    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" v-if="user">
        <div class="container">
            <a class="navbar-brand" href="/items">
                Auction
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="/items">Auction</a></li>
                    <li class="nav-item"><a class="nav-link" href="/settings">Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/logout" @click.prevent="logout">({{user.name}}) Logout</a></li>

                </ul>
            </div>
        </div>
    </nav>

</template>

<script>
    import auth from '../mixins/auth';

    export default {
        mixins: [auth],
        data() {
            return {
            }
        },
        methods: {
            logout() {
                localStorage.removeItem('token');
                window.location.href = '/';
            }
        },
        created() {

        }
    }

</script>
