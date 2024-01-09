<template>
<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-10">
                <h5 class="donation-titles">Products to be Delivered</h5>
                <table class="table mt-3" width="100%">
                    <thead class="theader">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Lot No.</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Cost</th>
                            <th scope="col">Total</th>
                            <th scope="col">Expiry Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="Object.keys(allocated_products).length !== 0 ">
                        <tr v-for="(allocated_product, index) in allocated_products" :key="index">
                            <td>{{ allocated_product.id }}</td>
                            <td>{{ allocated_product.product_name }}</td>
                            <td>{{ allocated_product.lot_no }}</td>
                            <td>{{ allocated_product.quantity }}</td>
                            <td>{{ allocated_product.unit_cost }}</td>
                            <td>{{ numberFormat (allocated_product.total) }}</td>
                            <td>{{ moment(allocated_product.expiry_date).format('MMMM D, YYYY') }}</td>
                            <td>
                                <button @click="getId(allocated_product.id)" data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn tt cfs-edit-btn" data-bs-placement="bottom" title="Delete" >
                                    <i class="fas fa-trash-alt cfs-edit-ic text-secondary"></i>
                                </button>
                            </td>
                        </tr>
                        </template>
                        <template v-else-if="Object.keys(allocated_products).length === 0">
                            <tr class="tableNoRecord">
                                <td colspan="8" align="center">No Record Found</td>
                            </tr>
                        </template>

                        <tr class="tableRecordStat">
                            <td></td>
                            <td></td>
                            <td>Total Quantity</td>
                            <td class="tableRecordStatAmount">{{this.numberFormat(medicine_total_quantity + promats_total_quantity)}}</td>
                            <td>Total Donation</td>
                            <td class="tableRecordStatAmount">Php {{this.numberFormat(total_donations.total_products_amount)}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <!-- Contribution Info Tab  -->
                <h5 class="donation-titles mt-4">Allocation Product</h5>
                <!-- Contribution Details Forms -->
                <form class="mt-3">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-md-3 col-form-label fw-bold">Principal :</label>
                                <div class="col-lg-8">
                                    <select class="form-control form-select" aria-label="Default select example" id="member_selection" @change="getInventory($event)">
                                        <option value="0" selected>Choose a Principal</option>
                                        <option v-for="(member, index) in members" :key="index" :value="member.id" > {{ member.member_name }} </option>
                                    </select>
                                </div>
                                <div v-if='inventory_loading' class="col-1 my-auto">
                                    <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row align-items-center">
                                <label for="" class="col-lg-3 col-form-label fw-bold">Products :</label>
                                <div class="col-lg-8">
                                    <!-- Show No Results Found if Inventory is empty -->
                                    <div v-if="Object.keys(inventory).length == 0"><span class="text-danger fw-bold">No Item Available</span></div>
                                    <!-- Show No Results Found IF Inventory is NOT empty -->
                                    <template v-else-if="Object.keys(inventory).length !== 0">
                                        <select class="form-control form-select" id="product_selection" aria-label="Default select example" @change="getSelectedProduct($event)">
                                            <option selected>Choose a Product</option>
                                            <option v-for="(item, index) in inventory" :key="index" :value="item.id"> {{ item.product_name }} </option>
                                        </select>
                                    </template>
                                </div>
                                <div v-if='item_loading' class="col-1 my-auto">
                                    <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Secleted Product Details -->
                    <div v-if="selected_product.id != 0">
                        <h5 class="donation-titles mt-4">Product Details</h5>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Principal :</label>
                                        <div class="col-lg-8">
                                            {{ selected_product.member_id }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Product Code :</label>
                                        <div class="col-lg-8">
                                            {{ selected_product.product_code }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Product Name :</label>
                                        <div class="col-lg-8">
                                            {{ selected_product.product_name }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Expiry Date :</label>
                                        <div class="col-lg-8">
                                            {{ moment(selected_product.expiry_date).format('l') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Unit Cost :</label>
                                        <div class="col-lg-8">
                                            {{ numberFormat (selected_product.unit_cost) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Lot No. :</label>
                                        <div class="col-lg-8">
                                            {{ selected_product.lot_no }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Available Stock :</label>
                                        <div class="col-lg-8">
                                            {{ numberFormat (selected_product.quantity) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Issuance Quantity :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="" id="issuance_quantity" name="issuance_quantity" v-model="issuance_quantity">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-success mt-3" @click="addAllocatedProduct()" :disabled='loading'>
                                <div v-if='loading'>
                                    <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                                    <span>Adding Product</span>
                                </div>
                                <span v-if='!loading'>Add Product</span>
                            </button>
                            <!-- End Delivery Details -->
                    </div>
                    <div class="d-flex flex-row-reverse mt-3">
                        <!-- <button type="button" class="btn btn-secondary">Cancel</button> -->
                        <button @click="saveTotalDonation()" type="button" class="btn btn-primary" :disabled='total_loading'>
                            <div v-if='total_loading'>
                                <div class="spinner-border text-light spinner-border-sm" role="status"></div>
                                <span>Saving</span>
                            </div>
                            <span v-if='!total_loading'>Save and Proceed</span>
                        </button>
                        <a :href="'/allocations/create/'+allocation_id" type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                    </div>
                </form>
        </div>
        <!-- Statistics -->
        <div class="col-md-2">
            <div class="card shadow stats-card">
                <div class="card-header stats-card-header">
                    Statistics
                </div>
                <div class="card-body stats-card-body">
                    <div class="stats-head-title">
                        <i class="fa-solid fa-box"></i>
                        Products
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                Medicine / Vaccine
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                {{ medicine_count }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                Promats
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                {{ promats_count }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                Total Products
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                {{ total_products_count }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="stats-head-title">
                    <i class="fa-solid fa-wallet"></i>
                    Amount
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="stats-title">
                                Medicine Amount
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                Php {{ numberFormat (total_donations.medicine_total_donation) }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="stats-title">
                                Promats Amount
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                Php {{ numberFormat (total_donations.promats_total_donation) }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="stats-title">
                                Total Donation
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                Php {{ numberFormat (total_donations.total_products_amount) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
        <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button @click="deleteProduct(p_id)" type="button" class="btn btn-danger">Confirm</button>
            </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
var moment = require('moment');

export default {
    props: ['allocation_id'],
    data: function() {
        return {
            moment:moment,
            allocation:{},
            members:[],
            inventory:[],
            selected_product:{
                id:0,
                member_id:0,
                allocation_id:0,
                inventory_id:1,
                product_type:"",
                product_code:"",
                product_name:"",
                quantity:0,
                lot_no:"",
                mfg_date:"",
                expiry_date:"",
                drug_reg_no:"",
                unit_cost:0,
                total:"",
                medicine_status:"",
                job_no:"",
                status:""},
            allocated_products:[],
            total_donations : {
                medicine_total_donation : 0,
                promats_total_donation : 0,
                total_products_amount : 0
            },
            p_id : 0,
            p_quantity : 0,
            medicine_total_quantity : 0,
            promats_total_quantity : 0,
            promats_count : 0,
            medicine_count : 0,
            total_products_count : 0,
            issuance_quantity: 0,
            loading:false,
            total_loading:false,
            inventory_loading:false,
            item_loading:false,
        }
    },
    methods : {
        getInventory (event) {
            this.inventory_loading = !false
            this.inventory.splice(0);
            this.$delete(this.selected_product);
            this.selected_product.id = 0;
            // console.log(this.inventory);
            axios.get('../../../inventory/' + event.target.value + "/" + this.allocation_id +"/show-inventory", {
            })
            .then( response => {
                this.inventory = response.data;
                this.inventory_loading = !true
            })
            .catch (error => {
                console.log( error );
            })
        },
        getMembers () {
            // this.$delete(this.inventory);
            axios.get('../../../members/show-members', {
            })
            .then( response => {
                this.members = response.data;
                console.log( "got members" );

            })
            .catch (error => {
                console.log( error );
            })
        },
        getSelectedProduct (event) {
            this.item_loading = !false
            if(isNaN(event.target.value)) {
                // if the selected product is the placeholder. delete the details template
                this.item_loading = !true
                this.selected_product.id = 0;
            } else {
                // if there is a selected item in invetory get the details of the product
                axios.get('../../../inventory/'+ event.target.value +"/show-product", {
                })
                .then( response => {
                    this.selected_product = response.data;
                    this.item_loading = !true
                })
                .catch (error => {
                    console.log( error );
                })
            }
        },
        addAllocatedProduct () {
            this.loading = !false
            if(this.issuance_quantity == 0) {
                this.loading = !true
                alert("Issuance quantity is empty");
                return;
            }

            if(this.selected_product.quantity < this.issuance_quantity) {
                this.loading = !true
                alert("Not enough stock.");
                return;
            }

            this.selected_product.expiry_date = moment(this.selected_product.expiry_date).format('l');
            this.selected_product.mfg_date = moment(this.selected_product.mfg_date).format('l');

            this.selected_product.inventory_id = this.selected_product.id;
            this.selected_product.medicine_status = "test";

            // adding the issuance quantity 
            this.selected_product.quantity = this.issuance_quantity;

            axios.post('../../../allocation/'+ this.allocation_id + "/save-allocated-product", {
                selected_product: this.selected_product
            })
            .then( response=> {
                if (response.status == 201) {
                    this.getAllocatedProducts ();
                    // Remove selected product template after adding product
                    this.issuance_quantity = 0;
                    this.members.splice(0);
                    this.inventory.splice(0);
                    this.selected_product.id = 0;
                    this.getMembers();
                    this.loading = !true
                }
            })
            .catch (error => {

            })
        },
        deleteProduct (id) {
            // var deletemodal = document.getElementById('deleteModal'); // relatedTarget
            axios.delete('../../../allocation/'+ id + "/delete-allocated-product")
            .then( response=> {
                if (response.status == 200) {
                    this.getAllocatedProducts ();
                    $('#deleteModal').modal('hide');
                }
            })
            .catch (error => {

            })
        },
        getAllocatedProducts () {
            axios.get('../../../allocation/'+ this.allocation_id + "/get-allocated-product", {
            })
            .then( response => {

                this.medicine_total_quantity = 0;
                this.total_donations.medicine_total_donation = 0;
                this.promats_total_quantity = 0;
                this.total_donations.promats_total_donation = 0;
                this.promats_count = 0;
                this.medicine_count = 0;

                this.allocated_products = response.data;
                
                this.allocated_products.forEach((item, index) => {
                    if(item.product_type === '1') {
                        this.medicine_total_quantity += item.quantity;
                        this.total_donations.medicine_total_donation += item.total;
                        this.medicine_count += 1;
                    }
                    if(item.product_type === '2') {
                        this.promats_total_quantity += item.quantity;
                        this.total_donations.promats_total_donation += item.total;
                        this.promats_count += 1;
                    }
                })
                
                this.total_donations.total_products_amount = this.total_donations.medicine_total_donation + this.total_donations.promats_total_donation;
                this.total_products_count = this.promats_count + this.medicine_count;
                
            })
            .catch (error => {
                console.log( error );
            })
        },
        getId(id) {
            this.p_id = id;
        },
        numberFormat (value) {
            return value.toLocaleString();
        },
        saveTotalDonation() {
            this.total_loading = !false
            if(Object.keys(this.allocated_products).length == 0) {
                this.total_loading = !true
                alert("Donations are empty");
                return;
            }
            axios.post('../../../allocation/'+ this.allocation_id + "/save-total-donations", {
                total_donations: this.total_donations
            })
            .then( response=> {
                if (response.status == 200) {
                    this.total_loading = !true
                    window.location.replace("/allocations");
                }
            })
            .catch (error => {

            })

        }
    },
    created() {
        this.getMembers ();
        this.getAllocatedProducts ();
    }, 

}
</script>



