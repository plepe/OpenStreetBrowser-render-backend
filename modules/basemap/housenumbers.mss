.housenumbers[zoom>=17] number {
  text-size: 8;
  text-placement: line;
  text-face-name: "DejaVu Sans Book";
  text-fill: #505050;
  text-avoid-edges: false;
  text-allow-overlap: true;
  text-halo-radius: 0;
  text-halo-fill: #f2efd9;
  /* Debug: show lines on which housenumbers are placed
  line-color: #0000ff;
  line-width: 1; */
}
.housenumber_lines[zoom>=17] {
  line-color: #505050;
  line-width: 0.5;
  line-dasharray: 2, 4;
}
