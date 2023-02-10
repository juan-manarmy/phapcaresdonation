<template>
    <div class="d-flex mt-2"> 
        <button type="button" class="btn btn-outline-success mt-2 fw-bold" @click="showDonation()">Add Donation</button>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        methods : {
        showDonation () {
            axios.get('/api/product-donation/show-donations', {
            })
            .then( response=> {
                if (response.status == 201) {
                }
            })
            .catch (error => {

            })
        }
    }
    }
</script>
