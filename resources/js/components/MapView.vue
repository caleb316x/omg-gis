<template>
  <div>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
      Grave Legend
    </button>
    <hr>
    <div class="input-group mb-3">
      <span class="input-group-text" id="basic-addon1">Search</span>
      <input type="text" class="form-control" placeholder="Search Name" aria-label="Username"
        aria-describedby="basic-addon1">
    </div>

    <div id="map"></div>
    <br />
    <div v-if="isAdmin">
      <div class="btn-area">
        <button class="btn btn-sm btn-primary" v-on:click="drawTools()" v-show="!drawing" id="draw">
          Add Plot
        </button>
        <button class="btn btn-sm btn-success" v-show="drawing" id="savedraw" v-on:click="createPlot()">
          Save
        </button>
        <button class="btn btn-sm btn-danger" v-on:click="drawTools()" v-show="drawing" id="canceldraw">
          Cancel
        </button>
      </div>
      <hr />
      <div class="row">
        <h5>Grave Information</h5>
        <div class="col-md">
          <div class="mb-3">
            <label class="form-label">Grave Type:</label>
            <select name="" class="form-select" v-model="grave_data.type">
              <option value="0">Type A (₱20,000)</option>
              <option value="1">Type B (₱50,000)</option>
              <option value="2">Type C (₱80,000)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Block:</label>
            <select name="" class="form-select" v-model="grave_data.block">
              <option value="0">A</option>
              <option value="1">B</option>
              <option value="2">C</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Area:</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Length</span>
              <input type="number" class="form-control" placeholder="In meters" aria-label="length"
                aria-describedby="basic-addon1" v-model="grave_data.area_length" />
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Width</span>
              <input type="number" class="form-control" placeholder="In meters" aria-label="length"
                aria-describedby="basic-addon1" v-model="grave_data.area_width" />
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="mb-3">
            <label class="form-label">Status:</label>
            <select name="status" class="form-select" v-model="grave_data.status">
              <option value="0">Available</option>
              <option value="1">Maintenance</option>
            </select>
          </div>
          <div class="mb-3">
            <!-- <label class="form-label">Buyer:</label>
            <select name="" class="form-select" id="">
              <option value="null">None</option>
              <option value="0">Caleb</option>
              <option value="1">Sanchez</option>
            </select> -->
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="gravemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">View Grave</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <p>Grave type: {{ graveinfo }}</p> -->
            <p>Grave type: {{ graveinfo.type }}</p>
            <p>Price: {{ moneyformat(graveinfo.price) }}</p>
            <p>Block: {{ graveinfo.block }}</p>
            <p>Area (LxW) meters: {{ graveinfo.area_length }} x {{ graveinfo.area_width }}</p>
            <p v-if="graveinfo.clientID != null">Client name: {{ fullname }}</p>
            <p v-if="graveinfo.name != null">Deceased name: {{ graveinfo.name }}</p>
            <p v-if="graveinfo.name != null">Date of Birth: {{ datetoString(graveinfo.birth_date) }}</p>
            <p v-if="graveinfo.name != null">Date of Death: {{ datetoString(graveinfo.death_date) }}</p>

            <div v-if="isFrontdesk">
              <hr>
              <div class="mb-3 mt-3">
                <label for="email" class="form-label">Client:</label>
                <select class="form-select" ref="selectedItem">
                  <option value="null"> ---- </option>
                  <option v-for="client in clientlist" :value="client.id" :selected="client.id == graveinfo.clientID">
                    {{ client.first_name }} {{ client.middle_name }} {{ client.last_name }} {{ client.id ==
                        graveinfo.clientID
                    }}
                  </option>
                </select>
              </div>
              <div class="mb-3 mt-3">
                <label for="email" class="form-label">Deceased name:</label>
                <input type="text" ref="Dname" class="form-control" placeholder="Name">
              </div>
              <div class="mb-3 mt-3">
                <label for="email" class="form-label">Date of Birth:</label>
                <input type="date" ref="Bdate" class="form-control">
              </div>
              <div class="mb-3 mt-3">
                <label for="email" class="form-label">Date of Death:</label>
                <input type="date" ref="Ddate" class="form-control">
              </div>
              <button class="btn btn-sm btn-success" v-on:click="UpdateGrave(graveinfo.id)">Update</button>
            </div>

          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <!-- <a class="btn btn-primary" href="login.html">Logout</a> -->
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Grave Legend</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <table border="1" width="100px">
                  <tr>
                    <th colspan="2">
                      <center>Grave Status</center>
                    </th>
                  </tr>
                  <tr>
                    <th>Type</th>
                    <th>Fill Color</th>
                  </tr>
                  <tr>
                    <td>Available</td>
                    <td bgcolor="#00a1c2"></td>
                  </tr>
                  <tr>
                    <td>Occupied</td>
                    <td bgcolor="#00FF00"></td>
                  </tr>
                  <tr>
                    <td>Maintenance</td>
                    <td bgcolor="#B22222"></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table border="1px solid black" width="100px">
                  <tr>
                    <th colspan="2">
                      <center>Grave type</center>
                    </th>
                  </tr>
                  <tr>
                    <th>Type</th>
                    <th>Border Color</th>
                  </tr>
                  <tr>
                    <td>A</td>
                    <td bgcolor="#FF0000"></td>
                  </tr>
                  <tr>
                    <td>B</td>
                    <td bgcolor="#0000FF"></td>
                  </tr>
                  <tr>
                    <td>C</td>
                    <td bgcolor="#008000"></td>
                  </tr>
                </table>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    isAdmin: Boolean,
    isFrontdesk: Boolean,
  },
  data() {
    return {
      center: { lat: 10.0, lng: 10.0 },
      markers: [
        {
          position: { lat: 10.0, lng: 10.0 },
        },
        {
          position: { lat: 11.0, lng: 11.0 },
        },
      ],
      drawing: false,
      map: null,
      drawman: null,
      setSelection: null,
      shapes: [],
      selected_shape: null,
      clearShapes: null,
      clearSelection: null,
      infowindow: null,
      clientlist: null,
      selectedClient: null,
      dName: "",
      fullname: "",
      BDate: null,
      DDate: null,
      graveinfo: {
        id: null,
        birth_date: null,
        death_date: null,
        Scolor: null,
        Fcolor: null,
        type: null,
        status: null,
        price: null,
        name: null,
        area_length: null,
        area_width: null,
        block: null,
        clientID: null,
      },
      grave_data: {
        type: 1,
        price: null,
        name: null,
        area_length: null,
        area_width: null,
        block: 0,
        status: 0,
        shape_data: {
          coordinates: null,
          type: null,
        },
      },
    };
  },
  mounted() {
    this.initMap();
    this.getPlots();
    console.log("isAdmin", this.isAdmin);
    console.log("isFrontdesk", this.isFrontdesk);
  },
  methods: {
    initMap() {
      var ts = this;
      console.log("Init Map");
      this.map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 11.0047325, lng: 124.6209895 },
        zoom: 19,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        streetViewControl: false,
      });

      ts.infowindow = new google.maps.InfoWindow({});

      this.clearSelection = function () {
        if (ts.selected_shape) {
          ts.selected_shape.set(
            ts.selected_shape.type === google.maps.drawing.OverlayType.MARKER
              ? "draggable"
              : "editable",
            false
          );
          ts.selected_shape = null;
        }
      };

      this.clearShapes = function () {
        for (var i = 0; i < shapes.length; ++i) {
          ts.shapes[i].setMap(null);
        }
        ts.shapes = [];
      };

      this.drawman = new google.maps.drawing.DrawingManager({
        // map: null,
        // map: this.map,
        drawingControlOptions: {
          position: google.maps.ControlPosition.TOP_CENTER,
          drawingModes: [
            google.maps.drawing.OverlayType.POLYGON,
            google.maps.drawing.OverlayType.MARKER,
          ],
        },
        polygonOptions: {
          strokeWeight: 2,
          editable: false,
          // draggable: true,
          zIndex: 1,
          fillOpacity: 0.5,
        },
      });

      this.setSelection = function (shape) {
        ts.clearSelection();
        ts.selected_shape = shape;

        ts.selected_shape.set(
          ts.selected_shape.type === google.maps.drawing.OverlayType.MARKER
            ? "draggable"
            : "editable",
          true
        );
        ts.selected_shape.set("draggable", true);
        ts.selected_shape.set("editable", true);
        ts.selected_shape.set("drawn", true);
      };

      google.maps.event.addListener(
        this.drawman,
        "overlaycomplete",
        function (e) {
          var shape = e.overlay;
          shape.type = e.type;

          console.log("shape", shape);
          ts.drawman.setDrawingMode(null);
          google.maps.event.addListener(shape, "click", function () {
            ts.setSelection(this);
          });
          ts.setSelection(shape);
          ts.shapes.push(shape);
          console.log("shape length: ", ts.shapes.length);

          //disable drawtool after drawn
          ts.drawman.setMap(null);
          console.log("Get last shape");
          // ts.drawing = false;
        }
      );
    },
    drawTools() {
      if (this.drawing) {
        this.drawman.setMap(null);
        this.drawing = false;
        this.clearDrawn();
      } else {
        this.drawman.setMap(this.map);
        this.drawing = true;
      }
    },
    clearDrawn() {
      var lastdrawn = this.shapes[this.shapes.length - 1];
      if ("drawn" in lastdrawn) {
        lastdrawn.set("map", null);
        this.shapes.pop();
        console.log("After delete: ", this.shapes);
      }
    },
    createPlot() {
      var ts = this;
      var shape_data = this.shapes[this.shapes.length - 1];
      var shape_coordinates = shape_data.latLngs
        .getArray()[0]
        .Qd.map((coord) => {
          return {
            lat: coord.lat(),
            lng: coord.lng(),
          }
        });
      // console.log("shape_data", shape_data.latLngs.getArray()[0].Qd[0].lat());
      console.log("shape_coordinates", shape_coordinates);
      this.grave_data.shape_data = {
        coordinates: shape_coordinates,
        type: shape_data.type,
      };

      console.log("grave_data", this.grave_data);

      var requestOptions = {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        body: JSON.stringify(this.grave_data),
      };
      fetch("/plots", requestOptions)
        .then((res) => res.json())
        .then((res) => {
          console.log("res", res);
          this.clearDrawn();
          this.drawPlot(res[0]);
          this.drawTools()
        });


    },
    drawPlot(data) {

      var ts = this;

      var coords = data.coordinates.map(coord => {
        return { lat: coord.lat, lng: coord.long }
      });

      var bounds = new google.maps.LatLngBounds();

      for (var i = 0; i <= coords.length - 1; i++) {
        bounds.extend(new google.maps.LatLng(coords[i].lat, coords[i].lng));
      }

      var cenpos = new google.maps.LatLng(bounds.getCenter().lat(), bounds.getCenter().lng());


      const viewbtn = document.createElement("a");
      viewbtn.innerHTML = "View";
      viewbtn.classList.add("btn", "btn-sm", "btn-primary");
      viewbtn.setAttribute("href", "#");
      // viewbtn.setAttribute("data-toggle", "modal");
      // viewbtn.setAttribute("data-target", "#gravemodal");
      // viewbtn.setAttribute("v-on:click", "drawTools("+data.id+")");

      var gdata = ts.graveMutateData(data);

      var content = "";
      if (ts.isFrontdesk) {
        // content = "<p> Grave type: <b>" + gdata.type + "</b></p> <p> Grave Status: <b>" + gdata.status + "</b></p><p> Area (LxW) meters: <b>" + data.area_length + " x " + data.area_width + "</b></p><p> Price: <b>" + ts.moneyformat(gdata.price) + "</b></p><p>" + viewbtn + "</p>";
        content = viewbtn;
      }
      else {
        if (gdata.status == "Occupied") {
          content = "<p> Grave type: <b>" + gdata.type + "</b></p>\
          <p>Name: <b>"+ gdata.name + "</b></p>\
          <p>Date of Birth: <b>"+ gdata.birth_date + "</b></p>\
          <p>Date of Death:<b>"+ gdata.death_date + "</b></p>\
          ";
        }
        else {
          content = "<p> Grave type: <b>" + gdata.type + "</b></p> <p> Grave Status: <b>" + gdata.status + "</b></p><p> Area (LxW) meters: <b>" + data.area_length + " x " + data.area_width + "</b></p><p> Price: <b>" + ts.moneyformat(gdata.price) + "</b></p>";
        }
      }



      var drawnshape = new google.maps.Polygon({
        path: coords,
        geodesic: true,
        strokeColor: gdata.Scolor,
        strokeOpacity: 1.0,
        strokeWeight: 1,
        fillOpacity: 1,
        fillColor: gdata.Fcolor,
        map: ts.map,
        arrgeo: coords,
        center: cenpos,
        iwc: content,
      });

      viewbtn.addEventListener("click", function () {
        ts.getGrave(data.id);
        $('#gravemodal').modal('toggle');
      });

      drawnshape.addListener('click', function () {
        console.log("clicked", this.center);
        ts.infowindow.close();
        ts.infowindow.setPosition(this.center);
        ts.infowindow.setContent(this.iwc);
        ts.infowindow.open(ts.map, this);
      });

      console.log("drawnshape", drawnshape);
      ts.shapes.push(drawnshape);
    },
    graveMutateData(data) {
      var id = data.id;
      var Scolor = "#FF0000";
      var Fcolor = "#00a1c2";
      var type = "";
      var status = "";
      var price = 0;
      var area_length = data.area_length;
      var area_width = data.area_width;
      var block = null;
      var clientID = data.user_id;
      var birth_date = data.birth_date;
      var death_date = data.death_date;

      if (data.type == "0") {
        Scolor = "#FF0000";
        type = "A";
        price = 20000;
      }
      if (data.type == "1") {
        Scolor = "#0000FF";
        type = "B";
        price = 50000;
      }
      if (data.type == "2") {
        Scolor = "#008000";
        type = "C";
        price = 80000;
      }

      if (data.block = 0) {
        block = "A";
      }
      if (data.block = 1) {
        block = "B";
      }
      if (data.block = 2) {
        block = "C";
      }

      if (data.status == 0) {
        Fcolor = "#00a1c2";
        status = "Available";
      }

      if (data.status == 1) {
        Fcolor = "#B22222";
        status = "Maintenance";
      }


      if (clientID != null) {
        Fcolor = "#00FF00";
        status = "Occupied";
      }

      return {
        id: id,
        Scolor: Scolor,
        Fcolor: Fcolor,
        type: type,
        status: status,
        price: price,
        name: data.name,
        area_length: area_length,
        area_width: area_width,
        block: block,
        clientID: clientID,
        birth_date,
        death_date,
      }
    },
    getPlots() {
      const ts = this;
      fetch("/getplots")
        .then((res) => res.json())
        .then((res) => {
          console.log("plots", res);
          res.forEach(plot => {
            ts.drawPlot(plot)
          });
        });
    },
    getClientList() {
      const ts = this;
      fetch("/clientlist")
        .then((res) => res.json())
        .then((res) => {
          console.log("clients", res);
          ts.clientlist = res;
        });
    },
    moneyformat(value) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
      }).format(value);
    },
    UpdateGrave(user_id) {
      var selID = this.$refs.selectedItem.value
      var InpDName = this.$refs.Dname.value
      var bdate = this.$refs.Bdate.value
      var ddate = this.$refs.Ddate.value
      console.log("bdate", bdate);
      const ts = this;
      fetch("/graveUpdate/" + user_id, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ "name": InpDName, "user_id": selID, "birth_date": bdate, "death_date": ddate }),
      })
        .then((res) => res.json())
        .then((res) => {
          console.log("Updategrave", res);
          $('#gravemodal').modal('toggle');
        });
    },
    getGrave(id) {
      const ts = this;
      this.getClientList();
      fetch("/getgrave/" + id)
        .then((res) => res.json())
        .then((res) => {
          console.log("grave", res);
          ts.graveinfo = ts.graveMutateData(res);
          console.log("gi", ts.graveinfo)
          ts.getUser(res.user_id)
          ts.$refs.Dname.value = res.name;
        });
    },
    getUser(id) {
      const ts = this;
      fetch("/getuser/" + id)
        .then((res) => res.json())
        .then((res) => {
          console.log("user", res);
          ts.fullname = res.first_name + " " + res.middle_name + " " + res.last_name;
        });
    },
    datetoString(date) {
      var d = new Date(date);
      var month = d.getMonth() + 1;
      var day = d.getDate();
      var year = d.getFullYear();
      return month + "/" + day + "/" + year;
    },
  },
};
</script>

<style scoped>
#map {
  /* background-color: teal; */
  border: solid teal 2px;
  /* width: 500px; */
  height: 500px;
}
</style>

            