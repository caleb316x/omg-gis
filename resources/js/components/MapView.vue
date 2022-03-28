<template>
  <div>
    asd
    <div id="map"></div>
  </div>
</template>

<script>
export default {
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
      map: null,
      drawman: null,
      setSelection: null,
      shapes: [],
      selected_shape: null,
      clearShapes: null,
      clearSelection: null,
    };
  },
  mounted() {
    this.initMap();
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
        map: this.map, 
        drawingControlOptions: {
          position: google.maps.ControlPosition.TOP_CENTER,
          drawingModes: [
            google.maps.drawing.OverlayType.POLYGON,
          ],
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
      };

      google.maps.event.addListener(
        this.drawman,
        "overlaycomplete",
        function (e) {
          var shape = e.overlay;
          shape.type = e.type;
          google.maps.event.addListener(shape, "click", function () {
            ts.setSelection(this);
          });
          ts.setSelection(shape);
          ts.shapes.push(shape);
        }
      );
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

            