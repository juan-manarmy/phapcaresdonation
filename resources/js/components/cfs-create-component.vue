<template>
    <div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Banner</label>
            <input class="form-control" type="file" name="banner_path" id="banner_path">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Details</label>
            <input type="text" class="form-control" name="details" id="details" aria-describedby="emailHelp" required>
        </div>

        <!-- <div class="mb-3" id="request_area">
            <div class="d-flex">
                <input type="text" class="form-control me-2" name="" id="" aria-describedby="emailHelp" required placeholder="Input request"> 
            </div>
        </div> -->

        <div class="mb-3" id="request_area">

            <label for="exampleInputEmail1" class="form-label">Request Items</label>

            <div class="d-flex mt-2"  v-for="key in count" :key="key"> 
                <input type="text text-white" class="form-control me-1" name="" :id="key"  v-model="values['dynamic-field-'+key]" placeholder="Input request"> 
            </div>

            <button class="btn btn-danger me-1 text-light" @click="remove()" > <i class="fa-solid fa-xmark"> </i></button>
            <button class="btn btn-companies me-1" id="btn_request"  @click="add()"><i class="fa-solid fa-plus"></i></button> 

            <!-- <span>
                <i class="fas fa-minus-circle" @click="remove(k)" v-show="k || ( !k && inputs.length > 1)"></i>
            </span> -->
        </div>

        <div class="mb-3 form-check">
            <input type='hidden' value='0' name='is_yearend'>
            <input type="checkbox" class="form-check-input" name="is_yearend" id="is_yearend" value="1">
            <label class="form-check-label" for="is_yearend">Is this a Year-End request?</label>
        </div>
<!--  -->
        <div class="d-grid">
            <button  @click="submit()" class="btn btn-primary">Submit</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                count: 1,
                values: {}
            }
        },
        methods: {
            add() {
                // this.inputs.push({ name: index });
                // console.log(this.inputs)\
                this.count++;
                console.log( this.count);
            },
            remove() {
                // this.inputs.splice(index, 1);
                this.count--;
            }
            ,submit() {
                for (var key of Object.keys(this.values)) {
                    console.log(key + " -> " + this.values[key])
                }

                console.log(Object.keys(this.values));
            }
        }
    }
</script>

