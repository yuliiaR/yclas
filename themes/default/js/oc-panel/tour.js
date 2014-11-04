$(function (){
// Instance the tour
    var tour = new Tour({
        container: "#content"
    });

    tour.addSteps([
        {
           element: "#page-welcome",
           title: getChosenLocalization("step1_title"),
           content: getTourLocalization("step1_content"),
           path: "/oc-panel",
           placement: "top",
        },
        {
           element: "#page-categories",
           title: "",
           content: getTourLocalization("step2_content"),
           path: "/oc-panel/category",
           placement: "top",
        },
        {
           element: "#page-general-configuration",
           title: "",
           content: getTourLocalization("step3_content"),
           path: "/oc-panel/settings/general",
           placement: "top",
        },
        {
           element: "#page-themes",
           title: "",
           content: getTourLocalization("step4_content"),
           path: "/oc-panel/theme",
           placement: "top",
        },
        {
           element: "#oc-faq",
           title: "",
           content: getTourLocalization("step5_content"),
           path: "/oc-panel/theme",
           placement: "left",
    }
    ]);

    // Initialize the tour
    tour.init();

    // Start the tour
    tour.start();
});