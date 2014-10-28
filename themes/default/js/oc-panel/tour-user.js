$(function (){
// Instance the tour
    var tour = new Tour();

    tour.addSteps([
        {
           element: "#page-welcome",
           title: getChosenLocalization("step6_title"),
           content: getTourLocalization("step6_content"),
           path: "/oc-panel/profile",
           placement: "top",
        },
        {
           element: "#page-my-dvertisements",
           title: "",
           content: getTourLocalization("step7_content"),
           path: "/oc-panel/profile/ads",
           placement: "top",
        },
        {
           element: "#page-edit-profile",
           title: "",
           content: getTourLocalization("step8_content"),
           path: "/oc-panel/profile/edit",
           placement: "top",
        },
        {
           element: "#menu-profile-options",
           title: "",
           content: getTourLocalization("step9_content"),
           path: "/oc-panel/profile/edit",
           placement: "right",
        },
        {
           element: "#visit-website",
           title: "",
           content: getTourLocalization("step10_content"),
           path: "/oc-panel/profile/edit",
           placement: "left",
    }
    ]);

    // Initialize the tour
    tour.init();

    // Start the tour
    tour.start();
});