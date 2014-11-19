$(function (){
// Instance the tour
    var tour = new Tour({
        container: "#content"
    });

    tour.addSteps([
        {
           element: "#page-welcome",
           title: getChosenLocalization("step6_title"),
           content: getTourLocalization("step6_content"),
           path: "/oc-panel/profile",
           placement: "top",
           redirect: false,
        },
        {
           element: "#page-my-dvertisements",
           title: "",
           content: getTourLocalization("step7_content"),
           path: "/oc-panel/profile/ads",
           placement: "top",
           redirect: false,
        },
        {
           element: "#page-edit-profile",
           title: "",
           content: getTourLocalization("step8_content"),
           path: "/oc-panel/profile/edit",
           placement: "top",
           redirect: false,
        },
        {
           element: "#menu-profile-options",
           title: "",
           content: getTourLocalization("step9_content"),
           path: "/oc-panel/profile/edit",
           placement: "right",
           redirect: false,
        },
        {
           element: "#visit-website",
           title: "",
           content: getTourLocalization("step10_content"),
           path: "/oc-panel/profile/edit",
           placement: "left",
           redirect: false,
    }
    ]);

    // Initialize the tour
    tour.init();

    // Start the tour
    tour.start();
});