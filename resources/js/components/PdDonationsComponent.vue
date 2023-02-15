<template>
    <div class="row">
        <div class="col-md-10">
            <!-- Medicine Table -->
            <div class="d-flex justify-content-between">
                <h5 class="donation-titles mt-2">Medicine Donations</h5>
                <h5 class="donation-titles mt-2">Contribution No. : {{ contribution_no }}</h5>
            </div>
            <!-- :value="contribution_id" -->
            <table class="table mt-3">
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
                    <template v-if="Object.keys(donations).length !== 0">
                        <tr v-for="(donation, index) in donations" :key="index">
                            <template v-if="donation.product_type === '1'">
                                <td>{{ donation.id }}</td>
                                <td>{{ donation.product_name }}</td>
                                <td>{{ donation.lot_no }}</td>
                                <td>{{ donation.quantity }}</td>
                                <td>{{ donation.unit_cost }}</td>
                                <td>{{ numberFormat (donation.total) }}</td>
                                <td>{{ moment(donation.expiry_date).format('MMMM D, YYYY') }}</td>
                                <td>
                                    <a :href="'/product-donation/'+donation.id+'/edit-donation/'" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                        <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                    </a>
                                    <button @click="getId(donation.id)"   data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn tt cfs-edit-btn" data-bs-placement="bottom" title="Delete">
                                        <i class="fas fa-trash-alt cfs-edit-ic text-secondary"></i>
                                    </button>
                                </td>
                            </template>
                        </tr>
                    </template>
                    <template v-else-if="Object.keys(donations).length === 0">
                        <tr class="tableNoRecord">
                            <td colspan="8" align="center">No Record Found</td>
                        </tr>
                    </template>
                    
                    <tr class="tableRecordStat">
                        <td></td>
                        <td></td>
                        <td>Total Quantity</td>
                        <td class="tableRecordStatAmount">{{this.numberFormat(medicine_total_quantity)}}</td>
                        <td>Total Donation</td>
                        <td class="tableRecordStatAmount">Php {{this.numberFormat(total_donations.medicine_total_donation)}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <h5 class="donation-titles mt-2">Promats Donations</h5>
            <!-- Promats Table -->
            <table class="table mt-3">
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
                    <template v-if="Object.keys(donations).length !== 0 ">
                        <tr v-for="(donation, index) in donations" :key="index">
                            <template v-if="donation.product_type === '2'">
                                <td>{{ donation.id }}</td>
                                <td>{{ donation.product_name }}</td>
                                <td>{{ donation.lot_no }}</td>
                                <td>{{ donation.quantity }}</td>
                                <td>{{ donation.unit_cost }}</td>
                                <td>{{ numberFormat (donation.total) }}</td>
                                <td>{{ moment(donation.expiry_date).format('MMMM D, YYYY') }}</td>
                                <td>
                                    <a :href="'/product-donation/'+donation.id+'/edit-donation/'" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                        <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                    </a>
                                    <button @click="getId(donation.id)" data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn tt cfs-edit-btn" data-bs-placement="bottom" title="Delete" >
                                        <i class="fas fa-trash-alt cfs-edit-ic text-secondary"></i>
                                    </button>
                                </td>
                            </template>
                        </tr>
                    </template>
                    <template v-else-if="Object.keys(donations).length === 0">
                        <tr class="tableNoRecord">
                            <td colspan="8" align="center">No Record Found</td>
                        </tr>
                    </template>
                    
                    <tr class="tableRecordStat">
                        <td></td>
                        <td></td>
                        <td>Total Quantity</td>
                        <td class="tableRecordStatAmount">{{this.numberFormat(promats_total_quantity)}}</td>
                        <td>Total Donation</td>
                        <td class="tableRecordStatAmount">Php {{this.numberFormat(total_donations.promats_total_donation)}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <h5 class="donation-titles mt-4">Add Product</h5>
            <hr>
            <!-- Medicine Donation Forms -->
            <form class="mt-3">
                <div class="row mt-2">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label class="col-lg-4 col-form-label fw-bold" for="">Product Type :</label>
                            <div class="col-lg-8">
                                <select class="form-control form-select" id="product_type" name="product_type" v-model="donation.product_type">
                                    <option selected value="1" >Medicine / Vaccine</option>
                                    <option value="2">Promotional Materials</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Brand Name / Product Name :</label>
                            <div class="col-lg-8">
                                <input type="text" :class="{'is-invalid':this.v$.product_name.$error}" class="form-control" name="product_name" id="product_name" placeholder="Brand Name / Product Name" v-model="donation.product_name">
                                <div v-if="this.v$.product_name.$error" class="invalid-feedback">
                                    Product Name is required
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2" v-if="donation.product_type === '1'">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Generic Name :</label>
                            <div class="col-lg-8">
                                <input type="text" :class="{'is-invalid':this.v$.generic_name.$error}" class="form-control" name="generic_name" id="generic_name" placeholder="Generic Name" v-model="donation.generic_name">
                                <div v-if="this.v$.generic_name.$error" class="invalid-feedback">
                                    Generic Name is required
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mt-2" v-if="donation.product_type === '1'">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Strength :</label>
                            <div class="col-lg-8">
                            <input type="text" :class="{'is-invalid':this.v$.strength.$error}" class="form-control" name="strength" id="strength" placeholder="Strength" v-model="donation.strength">
                            <div v-if="this.v$.strength.$error" class="invalid-feedback">
                                Strength is required
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2" v-if="donation.product_type === '1'">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Dosage Form :</label>
                            <div class="col-lg-8">
                            <input type="text" :class="{'is-invalid':this.v$.dosage_form.$error}" class="form-control" name="dosage_form" id="dosage_form" placeholder="Dosage Form" v-model="donation.dosage_form">
                            <div v-if="this.v$.dosage_form.$error" class="invalid-feedback">
                                Dosage Form is required
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2" v-if="donation.product_type === '1'">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Package Size :</label>
                            <div class="col-lg-8">
                            <input type="text" :class="{'is-invalid':this.v$.package_size.$error}" class="form-control" name="package_size" id="package_size" placeholder="Package Size" v-model="donation.package_size">
                            <div v-if="this.v$.package_size.$error" class="invalid-feedback">
                                Package Size is required
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Quantity :</label>
                            <div class="col-lg-8">
                            <input type="text" :class="{'is-invalid':this.v$.quantity.$error}" class="form-control" name="quantity" id="quantity" placeholder="Quantity" v-model="donation.quantity">
                            <div v-if="this.v$.quantity.$error" class="invalid-feedback">
                                Quantity is required
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Lot/Batch No. :</label>
                            <div class="col-lg-8">
                            <input type="text" :class="{'is-invalid':this.v$.lot_no.$error}" class="form-control" name="lot_no" id="lot_no" placeholder="Lot/Batch No." v-model="donation.lot_no">
                            <div v-if="this.v$.lot_no.$error" class="invalid-feedback">
                                Lot No. is required
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">MFG Date :</label>
                            <div class="col-lg-8">
                                <date-picker type="date" :class="{'is-invalid':this.v$.mfg_date.$error}" class="date-picker-wrap" input-class="form-control" format="MM-DD-YYYY" v-model="donation.mfg_date" placeholder="MFG Date" onkeydown="return false"></date-picker>
                                <!-- <input type="date" class="form-control" name="mfg_date" id="mfg_date" placeholder="MFG Date" v-model="donation.mfg_date"> -->
                                <div v-if="this.v$.mfg_date.$error" class="invalid-feedback">
                                    MFG Date is required
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Expiry Date :</label>
                            <div class="col-lg-8">
                                <date-picker type="date" :class="{'is-invalid':this.v$.expiry_date.$error}" class="date-picker-wrap" input-class="form-control" format="MM-DD-YYYY" v-model="donation.expiry_date" placeholder="Expiry Date" onkeydown="return false"></date-picker>
                                <div v-if="this.v$.expiry_date.$error" class="invalid-feedback">
                                    Expiry Date is required
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2" v-if="donation.product_type === '1'">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Drug Reg. No. :</label>
                            <div class="col-lg-8">
                                <input type="text" :class="{'is-invalid':this.v$.drug_reg_no.$error}" class="form-control" name="drug_reg_no" id="drug_reg_no" placeholder="Drug Reg. No." v-model="donation.drug_reg_no">
                                <div v-if="this.v$.drug_reg_no.$error" class="invalid-feedback">
                                    Drug Reg No. is required
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Unit Cost/ Trade Price :</label>
                            <div class="col-lg-8">
                            <input type="text" :class="{'is-invalid':this.v$.unit_cost.$error}" class="form-control" name="unit_cost" id="unit_cost" placeholder="Unit Cost/ Trade Price" v-model="donation.unit_cost">
                                <div v-if="this.v$.unit_cost.$error" class="invalid-feedback">
                                    Unit Cost is required
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="donation.product_type === '1'">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Medicine Status :</label>
                            <div class="col-lg-8">
                                <input type="text" :class="{'is-invalid':this.v$.medicine_status.$error}" class="form-control" name="medicine_status" id="medicine_status" placeholder="Medicine Status" v-model="donation.medicine_status">
                                <div v-if="this.v$.medicine_status.$error" class="invalid-feedback">
                                    Medicine Status is required
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- @click="addDonation()" -->
                <button type="button" class="btn btn-outline-success mt-2 fw-bold" @click="addDonation()">Add Donation</button>
            </form>
            <!--End Medicine Donation Forms -->
            <div class="d-flex flex-row-reverse">
                <button @click="saveTotalDonation()" class="btn btn-primary">Save and Proceed</button>
                <a :href="'/product-donation/initial-details/'+contribution_id" type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                <!-- <button onclick="history.back()" type="button" class="btn btn-outline-secondary me-2">Go Back</button> -->
            </div>
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
                        <div class="col">
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
                        <div class="col">
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
                        <div class="col">
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
                                Php {{ numberFormat (total_donations.medicine_total_donation)  }}
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
                    <button @click="deleteDonation(d_id)" type="button" class="btn btn-danger">Confirm</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .date-picker-wrap {
        display: block;
        width: 100%;
    }
</style>

<script>
var moment = require('moment');
import 'vue2-datepicker/index.css';
import DatePicker from 'vue2-datepicker';
import { reactive,computed } from '@vue/composition-api'
import useVuelidate from '@vuelidate/core';
import { required } from '@vuelidate/validators';

export default {
    props: {
        contribution_no: String,
        contribution_id: String
    },
    components: {
        DatePicker
    },
    setup (props) {
        const donation = reactive({
            contribution_id : props.contribution_id,
            strength : '',
            generic_name : '',
            product_type : "1",
            product_name : '',
            dosage_form : '',
            package_size: '',
            lot_no: '',
            quantity: 0,
            unit_cost: 0,
            drug_reg_no: '',
            expiry_date: '',
            mfg_date: '',
            medicine_status: '',
        })

        const rules = computed(() => {
            return {
                contribution_id : { required },
                strength : { required },
                generic_name :{ required },
                product_type : { required },
                product_name : { required },
                dosage_form : { required },
                package_size: { required },
                lot_no: { required },
                quantity: { required },
                unit_cost: { required },
                drug_reg_no: { required },
                expiry_date: { required },
                mfg_date: { required },
                medicine_status: { required },
            }
        })

        const v$ = useVuelidate(rules,donation)

        return { 
            donation,v$
        }
    },
    data: function() {
        return  {
            moment:moment,
            donations:[],
            d_id : 0,
            total_donations : {
                medicine_total_donation : 0,
                promats_total_donation : 0,
                total_products_amount : 0
            },
            medicine_total_quantity : 0,
            promats_total_quantity : 0,
            promats_count : 0,
            medicine_count : 0,
            total_products_count : 0,
        }
    },
    methods : {
        emptyToast() {
            var toastLiveExample = document.getElementById('liveToast')
            var toast = new bootstrap.Toast(toastLiveExample)
            toast.show()
        },
        saveTotalDonation() {
            if(Object.keys(this.donations).length == 0) {
                this.emptyToast()
                return;
            }
            axios.post('../../../api/product-donation/'+ this.donation.contribution_id + "/save-total-donations", {
                total_donations: this.total_donations
            })
            .then( response=> {
                if (response.status == 200) {
                    window.location.href = "/product-donation/" + this.donation.contribution_id  + "/secondary-details";
                }
            })
            .catch (error => {

            })

        },
        addDonation () {

            if(this.donation.product_type == 1) {
                this.v$.$validate()
            }

            if(this.v$.$error) {
                console.log(this.v$.$errors);
                return;
            }

            this.donation.expiry_date = moment(this.donation.expiry_date).format('l');
            this.donation.mfg_date = moment(this.donation.mfg_date).format('l');

            axios.post('../../../api/product-donation/'+ this.donation.contribution_id + "/save-donation", {
                donation: this.donation
            })
            .then( response=> {
                if (response.status == 201) {
                    this.donation.strength = '';
                    this.donation.generic_name = '';
                    this.donation.product_name = '';
                    this.donation.dosage_form = '';
                    this.donation.package_size = '';
                    this.donation.lot_no = '';
                    this.donation.quantity = '';
                    this.donation.unit_cost = '';
                    this.donation.drug_reg_no = '';
                    this.donation.expiry_date = '';
                    this.donation.mfg_date = '';
                    this.donation.medicine_status = '';

                    this.v$.$reset();
                    this.getDonations ();
                }
            })
            .catch (error => {

            })
        },
        getDonations () {
            axios.get('../../../api/product-donation/'+ this.contribution_id + "/show-donations", {
            })
            .then( response => {

                this.medicine_total_quantity = 0;
                this.total_donations.medicine_total_donation = 0;
                this.promats_total_quantity = 0;
                this.total_donations.promats_total_donation = 0;
                this.promats_count = 0;
                this.medicine_count = 0;

                this.donations = response.data;
                
                this.donations.forEach((item, index) => {
                    // If medicine
                    if(item.product_type === '1') {
                        this.medicine_total_quantity += item.quantity;
                        this.total_donations.medicine_total_donation += item.total;
                        this.medicine_count += 1;
                    }
                    
                    // If promats
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
        deleteDonation (id) {
            // var deletemodal = document.getElementById('deleteModal'); // relatedTarget
            axios.delete('../../../api/product-donation/'+ id + "/delete-donation")
            .then( response=> {
                if (response.status == 200) {
                    this.getDonations ();
                    $('#deleteModal').modal('hide');
                }
            })
            .catch (error => {

            })
        },
        getId (id) {
            this.d_id = id;
            console.log (this.d_id);
        },
        numberFormat (value) {
            return value.toLocaleString();
        }
    },
    created() {
        this.getDonations();
    }, 
    mounted() {

    }
}



</script>
