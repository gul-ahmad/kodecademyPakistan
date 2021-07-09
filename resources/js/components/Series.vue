<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Current Series</h3>
            <div class="card-tools">
              <button type="submit" class="btn btn-success" @click="newModal">
                <i class="fas fa-user-plus blue"></i>
              </button>
            </div>
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
                <tr v-for="serie in series" :key="serie.id">
                  
                  <td>{{serie.title}}</td>
                <!--   Below v-if are used to shorten the text of description -->
                  <td v-if="serie.description.length<8">{{ serie.description }}</td>
                  <td v-else>{{ serie.description.substring(0,20)+".." }}</td>
                 
                   <td> <!-- <img class="bd-placeholder-img" src="{{ url('/'.$serie->logo) }}"> -->
                   <img v-bind:src="serie.logo" alt="" title=""> 
                   
                   </td>

                  <td>
                    <a href="#">
                      <i class="fa fa-edit blue" @click="editModal(serie)"></i>
                    </a>
                    <a href="#" @click="deleteSeries(serie.id)">
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
    <div
      class="modal fade"
      id="addCourse"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addCourseModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCourseModalLabel">Add Series</h5>
            <!--  <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Update Series</h5> -->
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- form start -->
            <form @submit.prevent="editMode ? updateSeries() :  createSeries()">
              <div class="form-group">
                <label for="Name" class="col-form-label">Title</label>
                <div class="">
                  <input
                    v-model="form.title"
                    type="text"
                    name="title"
                    class="form-control"
                    id="title"
                    placeholder="Title"
                    :class="{ 'is-invalid': form.errors.has('title') }"
                  />
                 <!--  //last added this -->
                <div v-if="errors && errors.title" class="text-danger">{{ errors.title[0] }}</div>
                </div>
              </div>

              <div class="form-group">
                <label for="Description" class="col-form-label"
                  >Description</label
                >
                <div class="">
                  <input
                    v-model="form.description"
                    type="text"
                    name="description"
                    class="form-control"
                    id="description"
                    placeholder="Description"
                  />
                  <HasError :form="form" field="description" />
                    <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}</div>
                </div>
              </div>
              <div class="form-group">
                <label for="logo" class="col-form-label">Logo</label>
                <div class="">
                  <input
                    type="file"
                    v-on:change="onFileChange"
                    name="logo"
                    class="form-control"
                    id="logo"
                    placeholder="Logo"
                  />
                  
                  <HasError :form="form" field="logo" />
                   <div v-if="errors && errors.logo" class="text-danger">{{ errors.logo[0] }}</div>
                  <div v-if="form.progress">
                    Progress: {{ form.progress.percentage }}%
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">
                  Save changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
export default {
  data() {
    
    return {
     errors: {},
       series: {},
       //used EditMode here beacuse I am using one popup modal for both create and update forms
       //So edit mode is used 
      editMode: false,
      form: new Form({
        id: "",
        title: "",
        description: "",
        logo: null,
      
      }),
    };
  },

  methods: {
      updateSeries(){
 
            this.errors = {};
            //I am sending the form values using append method becuse the ther ways were not working for put action
            //after appending the form values i am sending them as argument in axios.post
         let formData = new FormData();
          formData.append('title', this.form.title);
          formData.append('description', this.form.description);
          formData.append('logo', this.form.logo);
          formData.append('_method', 'put');
      // console.log(formData.append('file',this.file));

             // console.log(formData);



             this.$Progress.start();
           //here i have used the post way of axios as put does not work 
           //to tell about the put method i have declared the put in the append above like we do in laravel

                axios.post('api/series/' +this.form.id,formData)

               .then(()=>{
                    $('#addCourse').modal('hide');
                 Swal.fire(
                        'updated!',
                        'Series Updated Successfully.',
                        'success'
                        )

                   this.$Progress.finish();
                   //last added this
                   this.form.errors.clear();
                       this.geSeriesList();
               })
               /* .catch((error)=>{
                 this.errors = errors.response.data.errors
                 Swal.fire("Failed","Something went wrong!","warning")


               }) */

               //last added this
               .catch(error => {
          this.loaded = true;
          //I have used this IF condition here becuase VForm error message was not displaying on validation 
          //failure....VFORM valiation error were showing only this.form.post but not on axios.post so 
          //I used this way to grab the errors and shows them in the form using this below way
             /*  <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}</div> */
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
 

            /* .catch (error => {
                 this.form.errors.all();
            }) */



       },

          editModal(serie)
          {
         this.editMode =true;
         this.form.reset();
          $("#addCourse").modal("show");
           this.form.fill(serie);

          },

    deleteSeries(id) {
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
                       this.form.delete('api/series/'+id).then(()=>{

                          Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        )
                           this.geSeriesList();

                       }).catch(()=>{

                      Swal.fire("Failed","something went wrong!");



                       })


                        
                    }
                    })
            
          },

    newModal() {
      this.editMode = false,
       this.form.reset();
      $("#addCourse").modal("show");
    },
    //this function is only for grabbing the file/image
    onFileChange(event) {
      const logo = event.target.files[0];
      // Set the file object onto the form...
      this.form.logo = logo;
    },
    createSeries() {
      // let formData = new FormData();
      //  formData.append('title', this.form.title);
      // formData.append('description', this.form.description);
      //  formData.append('logo', this.logo);
      // console.log(formData.append('file',this.file));

      this.form
        .post("series")
        .then((Response) => {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Series Added Successfully",
            showConfirmButton: false,
            timer: 1500,
          });
          this.$Progress.start();
          $("#addCourse").modal("hide");
          this.form.reset();
          this.$Progress.finish();
          $;
        })
        .catch((Response) => {
          this.$Progress.fail();
        });
      //  console.log('I am coming here in the createUser');
    },
  
         geSeriesList() {
           axios.get('api/series')
            .then(response => this.series = response.data);
           
                   //console.log(response);
              }




  },
  mounted() {
      this.geSeriesList();
     // console.log('I am here in the ready');
  },
  /* computed: {
   strippedDescription: function(){
      if(this.form.description.length > 10) {
         return this.form.description.slice(0,30);
      }
      return this.form.description;
   }
} */
}
</script>
