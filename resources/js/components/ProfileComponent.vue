<template>
    <div class="container">
       <div class="row">
         
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
             <h3>Profile Management</h3>
              </div><!-- /.card-header -->
              <div class="card-body">
              
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="Name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input v-model="form.name" type="text" name="name" class="form-control" id="name" placeholder="Name"
                          
                          >
                           <HasError :form="form" field="name" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input v-model="form.email" type="email" name="emal" class="form-control" id="email" placeholder="Email">
                           <HasError :form="form" field="email" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                          <input v-model="form.password" type="password" name="password" class="form-control" id="password" placeholder="Password">
                            <HasError :form="form" field="password" />

                        </div>
                      </div>
    
                  
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button @click.prevent="updateUserInfo" type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>
                    </form>
            
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
    </div>
</template>

<script>
import Form from 'vform';
import axios from 'axios';
    export default {
           data(){
            return {
                 form: new Form({
                  //  id:'',
                    name : '',
                    email: '',
                    password: ''
                   
                })
            }
        },
         methods:{

           updateUserInfo(){
            this.$Progress.start();
            //Remember we are using this.form.put not axios.put(as this was not working)
            this.form.put('api/profile')
            .then(()=>{
                 Swal.fire(
                        'updated!',
                        'Your Info has been Updated Successfully.',
                        'success'
                        )

               this.$Progress.finish();

            })
              .catch(() => {
                    this.$Progress.fail();
                });



           }



         },

        mounted() {
            console.log('Component mounted.')
        },

        created(){
           
            axios.get("api/profile")
            .then(({data}) =>(this.form.fill(data)));

              }
        }
    
</script>
