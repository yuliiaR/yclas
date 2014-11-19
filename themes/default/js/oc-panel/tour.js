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
           redirect: false,
        },
        {
           element: "#page-categories",
           title: "",
           content: getTourLocalization("step2_content"),
           path: "/oc-panel/category",
           placement: "top",
           redirect: false,
        },
        {
           element: "#page-general-configuration",
           title: "",
           content: getTourLocalization("step3_content"),
           path: "/oc-panel/settings/general",
           placement: "top",
           redirect: false,
        },
        {
           element: "#page-themes",
           title: "",
           content: getTourLocalization("step4_content"),
           path: "/oc-panel/theme",
           placement: "top",
           redirect: false,
        },
        {
           element: "#oc-faq",
           title: "",
           content: getTourLocalization("step5_content"),
           path: "/oc-panel/theme",
           placement: "left",
           redirect: false,
    }
    ]);

    // Initialize the tour
    tour.init();

    // Start the tour
    tour.start();
});