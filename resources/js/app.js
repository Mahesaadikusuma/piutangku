import "preline";

import ApexCharts from "apexcharts";
import moment from "moment";
import Pikaday from "pikaday";
import Quill from "quill";
import "quill/dist/quill.core.css"; // Import CSS agar tampilan muncul

window.Quill = Quill; // supaya bisa dipakai di Blade script

window.moment = moment;
window.Pikaday = Pikaday;
window.ApexCharts = ApexCharts;
