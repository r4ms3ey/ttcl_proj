// dashboard.js: Handles tab navigation and active state in admin dashboard

$(document).ready(function () {
    // When a tab is clicked
    $(".tabs .tab").click(function () {
        let page = $(this).data("page"); // Get page name from data attribute

        // Remove active class from all tabs, add to clicked tab
        $(".tabs .tab").removeClass("active");
        $(this).addClass("active");

        // Redirect to dashboard.php with selected tab as kurasa parameter
        window.location.href = "dashboard.php?kurasa=" + page;
    });

    // On page load, keep the correct tab active based on URL
    let urlParams = new URLSearchParams(window.location.search);
    let currentPage = urlParams.get("kurasa") || "attendance.php";

    // Loop through tabs and set active class for current page
    $(".tabs .tab").each(function () {
        if ($(this).data("page") === currentPage) {
            $(this).addClass("active");
        }
    });
});