$(function (){
// Instance the tour
    var tour = new Tour({
        container: "#content"
    });

    tour.addSteps([
        {
           element: "#page-welcome",
           title: getTourLocalization("step1_title"),
           content: getTourLocalization("step1_content"),
           path: getTourBasePath() + "oc-panel",
           placement: "top",
           redirect: false,
        },
        {
           element: "#page-categories",
           title: "",
           content: getTourLocalization("step2_content"),
           path: getTourBasePath() + "oc-panel/category",
           placement: "top",
           redirect: true,
        },
        {
           element: "#page-general-configuration",
           title: "",
           content: getTourLocalization("step3_content"),
           path: getTourBasePath() + "oc-panel/settings/general",
           placement: "top",
           redirect: true,
        },
        {
           element: "#page-themes",
           title: "",
           content: getTourLocalization("step4_content"),
           path: getTourBasePath() + "oc-panel/theme",
           placement: "top",
           redirect: true,
        },
        {
           element: "#oc-faq",
           title: "",
           content: getTourLocalization("step5_content"),
           path: getTourBasePath() + "oc-panel/theme",
           placement: "left",
           redirect: true,
    }
    ]);

    // Initialize the tour
    tour.init();

    // Start the tour
    tour.start();
});