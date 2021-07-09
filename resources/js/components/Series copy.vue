<template>
    <div class="container">
     <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Current Series</h3>
                <div class="card-tools"><button type="submit" class="btn btn-success" @click="newModal"><i class="fas fa-user-plus blue"></i></button> </div>

            
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                     
                      <th>Title</th>
                      <th>Description</th>
                      <th>Logo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr >
                      
                      <td>fasdf</td>
                      <td>dfdfdf</td>
                      
                      <td>
                       <a href="#">
                           <i class="fa fa-edit blue"></i>
                           
                         </a>   
                            <a href="#" >
                           <i class="fa fa-trash red"></i>
                           
                         </a>
                          
                     </td>
                    </tr>
                   
                   
                   
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
    <!-- Modal Add User -->
<div class="modal fade" id="addCourse" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"  id="addCourseModalLabel">Add Series</h5>
       <!--  <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Update Series</h5> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
              
              <!-- form start -->
              <form  @submit.prevent="createCourse()">
            
                  <div class="form-group">
                    <label for="Name" class="col-form-label">Title</label>
                    <div class="">
                      <input v-model="form.title" type="text"  name="title"
                       class="form-control"
                        id="title"
                         placeholder="Title"
                         :class="{ 'is-invalid': form.errors.has('title') }"
                         >
                      <HasError :form="form" field="title" />
                    </div>
                  </div>

                  <div class="form-group ">
                    <label for="Description" class="col-form-label">Description</label>
                    <div class="">
                      <input v-model="form.description" type="description"  name="description"  class="form-control"
                      
                       id="description" placeholder="Description">
                      <HasError :form="form" field="description" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="logo"   class="col-form-label">Logo</label>
                    <div class="">
                      <input type="file" v-on:change="onFileChange" name="logo"
                      
                       class="form-control" id="logo" placeholder="Logo">
                      <HasError :form="form" field="logo" />
                    </div>
                  </div>
                  
                <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit"  class="btn btn-primary">Save changes</button>
                           
                    </div>
               
              </form>

      </div>
    
    </div>
  </div>
</div>





    </div>
</template>

<script>
import Form from 'vform';
import axios from 'axios';
    export default {
             data(){
               
            return {
             //  errors: [],
            editMode : false,    
         //   users: {},
             form : new Form({
               id:'',
               title: '',
               description: '',
               logo: ''

             })

            }
            
             },

    methods: {
     /*      updateUser(){
              this.$Progress.start();
               this.form.put('api/user/' +this.form.id)
               .then(()=>{
                    $('#addUser').modal('hide');
                 Swal.fire(
                        'updated!',
                        'Your file has been Updated Successfully.',
                        'success'
                        )

                   this.$Progress.finish();

               })
               .catch(()=>{

                 Swal.fire("Failed","Something went wrong!","warning")


               })


             },


        editModal(user){
            this.editMode = true,
            this.form.reset();
           $('#addUser').modal('show');
           this.form.fill(user);



        }, */
            newModal(){
                this.editMode =false,    
            this.form.reset();
           $('#addCourse').modal('show');



        },
        
        deleteUser(id) {
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                       this.form.delete('api/user/'+id).then(()=>{

                          Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        )
                           this.loadUsers();

                       }).catch(()=>{

                      Swal.fire("Failed","something went wrong!");



                       })


                        
                    }
                    })
            
          },
           onFileChange(e) {
                //console.log(e.target.files[0]);
              //  this.logo = "Selected File: " + e.target.files[0].name;
                this.logo = e.target.files[0];
               console.log(this.logo);
            },
          createCourse() {
              /* const fileInput = document.querySelector('#logo');
         //    console.log(fileInput);

                let formData = new FormData();
                const mylogo =  formData.append( 'logo', fileInput.file[0] );
                  console.log(mylogo);
                formData.append( 'title', this.form.title );
                formData.append( 'description', this.form.description);

                  console.log( formData.append( 'logo', fileInput.files[0] )); */ 
                  let formData = new FormData();
                  formData.append('title', this.form.title);
                  formData.append('description', this.form.description);
                  formData.append('logo', this.logo);
                // console.log(formData.append('file',this.file));

           this.form.post('series',formData)
           .then((Response)=>{
             Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'User Added Successfully',
                    showConfirmButton: false,
                    timer: 1500
                    })
               this.$Progress.start()
               $('#addCourse').modal('hide');
               this.form.reset();
               this.$Progress.finish()
               $

           })
           .catch((Response)=>{
              this.$Progress.fail()

           });
          //  console.log('I am coming here in the createUser');


          },
           updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                let limit = 1024 * 1024 * 2;
                if(file['size'] > limit){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                }
                reader.onloadend = (file) => {
                    this.form.logo = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
      
  

          

           /* getUsersList() {
           axios.get('api/user')
            .then(response => this.users = response.data);

              } */

 
       created() {
           
          
         //  this.getUsersList();



            
        }
    }
</script>
