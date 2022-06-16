<template>
  <div>
    <div id="map"></div>
      <br />
    <div v-if="isAdmin">
      <div class="btn-area">
        <button
          class="btn btn-sm btn-primary"
          v-on:click="drawTools()"
          v-show="!drawing"
          id="draw"
        >
          Add Plot
        </button>
        <button
          class="btn btn-sm btn-success"
          v-show="drawing"
          id="savedraw"
          v-on:click="createPlot()"
        >
          Save
        </button>
        <button
          class="btn btn-sm btn-danger"
          v-on:click="drawTools()"
          v-show="drawing"
          id="canceldraw"
        >
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
              <input
                type="number"
                class="form-control"
                placeholder="In meters"
                aria-label="length"
                aria-describedby="basic-addon1"
                v-model="grave_data.area_length"
              />
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Width</span>
              <input
                type="number"
                class="form-control"
                placeholder="In meters"
                aria-label="length"
                aria-describedby="basic-addon1"
                v-model="grave_data.area_width"
              />
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
  </div>
</template>

<script>
export default {
  props: {
    isAdmin: Boolean,
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
      grave_data: {
        type: 1,
        price: null,
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

      console.log("grave_data",this.grave_data);

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
          console.log("res",res);
          this.clearDrawn();
          this.drawPlot(res[0]);
          this.drawTools()
      });

      
    },
    drawPlot(data) {

      var ts = this;

      var coords = data.coordinates.map(coord => {
        return {lat: coord.lat , lng: coord.long}
      });
        var Scolor= "#FF0000";
        var Fcolor = "#00a1c2";
        var Ctype = "";
        var Gstatus = "";
        var price = 0;

      if(data.type == "0"){
        Scolor = "#FF0000";
        Ctype = "A";
        price = 20000;
      }
      if(data.type == "1"){
        Scolor = "#0000FF";
        Ctype = "B";
        price = 50000;
      }
      if(data.type == "2"){
        Scolor = "#008000";
        Ctype = "C";
        price = 80000;
      }

      if(data.status == 0){
        Fcolor = "#00a1c2";
        Gstatus = "Available";
      }

      if(data.status == 1){
        Fcolor = "#B22222";
        Gstatus = "Maintenance";
      }

      var bounds = new google.maps.LatLngBounds();

      for(var i = 0;i<= coords.length-1;i++){
        bounds.extend(new google.maps.LatLng(coords[i].lat, coords[i].lng));
      }

      var cenpos =  new google.maps.LatLng(bounds.getCenter().lat(), bounds.getCenter().lng());

      var content = "<p> Grave type: <b>" + Ctype + "</b></p> <p> Grave Status: <b>" + Gstatus + "</b></p><p> Area (LxW) meters: <b>" +data.area_length+" x "+data.area_width+ "</b></p><p> Price: <b>" + ts.moneyformat(price) + "</b></p>";

      var drawnshape = new google.maps.Polygon({
        path: coords,
        geodesic: true,
        strokeColor: Scolor,
        strokeOpacity: 1.0,
        strokeWeight: 1,
        fillColor: Fcolor,
        map: ts.map,
        arrgeo: coords,
        center: cenpos,
        iwc: content,
      });

      drawnshape.addListener('click', function() {
        console.log("clicked",this.center);
        ts.infowindow.close();
        ts.infowindow.setPosition(this.center);
        ts.infowindow.setContent(this.iwc);
        ts.infowindow.open(ts.map, this);
      });	

      console.log("drawnshape", drawnshape);
      ts.shapes.push(drawnshape);
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
    moneyformat(value) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
      }).format(value);
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

            