<template>
    <div class="container">
     <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List</h3>
                <div class="card-tools"><button type="submit" class="btn btn-success" @click="newModal"><i class="fas fa-user-plus blue"></i></button> </div>

            
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                     
                      <th>Name</th>
                      <th>Email</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr  v-for="user in users" :key="user.id">
                      
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                      
                      <td>
                       <a href="#">
                           <i class="fa fa-edit blue" @click="editModal(user)"></i>
                           
                         </a>   
                            <a href="#"  @click="deleteUser(user.id)">
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
<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" v-show="!editMode" id="exampleModalLabel">Add User</h5>
        <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Update User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
              
              <!-- form start -->
              <form  @submit.prevent="editMode ? updateUser() : createUser()">
            
                  <div class="form-group">
                    <label for="Name" class="col-form-label">Name</label>
                    <div class="">
                      <input v-model="form.name" type="text"  name="name" class="form-control" id="name" placeholder="Name">
                      <HasError :form="form" field="name" />
                    </div>
                  </div>

                  <div class="form-group ">
                    <label for="Email" class="col-form-label">Email</label>
                    <div class="">
                      <input v-model="form.email" type="email"  name="email"  class="form-control"
                      
                       id="email" placeholder="Email">
                      <HasError :form="form" field="email" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="password"   class="col-form-label">Password</label>
                    <div class="">
                      <input v-model="form.password" type="password"  name="pasword" class="form-control" id="password" placeholder="Password">
                      <HasError :form="form" field="password" />
                    </div>
                  </div>
                  
                <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit"  v-show="!editMode" class="btn btn-primary">Save changes</button>
                            <button type="submit" v-show="editMode"  class="btn btn-primary">Update Info</button>
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
            editMode : false,    
            users: {},
             form : new Form({
                 id:'',
               name: '',
               email: '',
               password: ''

             })

            }
            
             },

    methods: {
          updateUser(){
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



        },
            newModal(){
                this.editMode =false,    
            this.form.reset();
           $('#addUser').modal('show');



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
          createUser() {
           this.form.post('api/user')
           .then((Response)=>{
             Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'User Added Successfully',
                    showConfirmButton: false,
                    timer: 1500
                    })
               this.$Progress.start()
               $('#addUser').modal('hide');
               this.form.reset();
               this.$Progress.finish()
               $

           })
           .catch((Response)=>{
              this.$Progress.fail()

           });
          //  console.log('I am coming here in the createUser');


          },

          

           getUsersList() {
           axios.get('api/user')
            .then(response => this.users = response.data);

              }

    },

       created() {
           
          
           this.getUsersList();



            
        }
    }
</script>
