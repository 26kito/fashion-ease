/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/helper.js":
/*!********************************!*\
  !*** ./resources/js/helper.js ***!
  \********************************/
/***/ (() => {

eval("var helper = {\n  rupiahFormatter: function rupiahFormatter(number) {\n    // Check if the input is a valid number\n    if (isNaN(number)) {\n      console.error('Invalid input. Please provide a valid number.');\n      return '';\n    }\n\n    // Convert the number to a string and add the currency symbol\n    var rupiah = 'Rp.' + Math.floor(number).toString().replace(/\\d(?=(\\d{3})+$)/g, '$&.');\n    return rupiah;\n  }\n};\nwindow.helper = helper;//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvaGVscGVyLmpzLmpzIiwibmFtZXMiOlsiaGVscGVyIiwicnVwaWFoRm9ybWF0dGVyIiwibnVtYmVyIiwiaXNOYU4iLCJjb25zb2xlIiwiZXJyb3IiLCJydXBpYWgiLCJNYXRoIiwiZmxvb3IiLCJ0b1N0cmluZyIsInJlcGxhY2UiLCJ3aW5kb3ciXSwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9oZWxwZXIuanM/M2Y2MCJdLCJzb3VyY2VzQ29udGVudCI6WyJjb25zdCBoZWxwZXIgPSB7XHJcblx0cnVwaWFoRm9ybWF0dGVyKG51bWJlcikge1xyXG5cdFx0Ly8gQ2hlY2sgaWYgdGhlIGlucHV0IGlzIGEgdmFsaWQgbnVtYmVyXHJcblx0XHRpZiAoaXNOYU4obnVtYmVyKSkge1xyXG5cdFx0XHRjb25zb2xlLmVycm9yKCdJbnZhbGlkIGlucHV0LiBQbGVhc2UgcHJvdmlkZSBhIHZhbGlkIG51bWJlci4nKTtcclxuXHRcdFx0cmV0dXJuICcnO1xyXG5cdFx0fVxyXG5cclxuXHRcdC8vIENvbnZlcnQgdGhlIG51bWJlciB0byBhIHN0cmluZyBhbmQgYWRkIHRoZSBjdXJyZW5jeSBzeW1ib2xcclxuXHRcdGxldCBydXBpYWggPSAnUnAuJyArIE1hdGguZmxvb3IobnVtYmVyKS50b1N0cmluZygpLnJlcGxhY2UoL1xcZCg/PShcXGR7M30pKyQpL2csICckJi4nKTtcclxuXHJcblx0XHRyZXR1cm4gcnVwaWFoO1xyXG5cdH1cclxufVxyXG5cclxud2luZG93LmhlbHBlciA9IGhlbHBlcjsiXSwibWFwcGluZ3MiOiJBQUFBLElBQU1BLE1BQU0sR0FBRztFQUNkQyxlQUFlLFdBQUFBLGdCQUFDQyxNQUFNLEVBQUU7SUFDdkI7SUFDQSxJQUFJQyxLQUFLLENBQUNELE1BQU0sQ0FBQyxFQUFFO01BQ2xCRSxPQUFPLENBQUNDLEtBQUssQ0FBQywrQ0FBK0MsQ0FBQztNQUM5RCxPQUFPLEVBQUU7SUFDVjs7SUFFQTtJQUNBLElBQUlDLE1BQU0sR0FBRyxLQUFLLEdBQUdDLElBQUksQ0FBQ0MsS0FBSyxDQUFDTixNQUFNLENBQUMsQ0FBQ08sUUFBUSxFQUFFLENBQUNDLE9BQU8sQ0FBQyxrQkFBa0IsRUFBRSxLQUFLLENBQUM7SUFFckYsT0FBT0osTUFBTTtFQUNkO0FBQ0QsQ0FBQztBQUVESyxNQUFNLENBQUNYLE1BQU0sR0FBR0EsTUFBTSJ9\n//# sourceURL=webpack-internal:///./resources/js/helper.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/helper.js"]();
/******/ 	
/******/ })()
;