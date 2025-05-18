function contentSetup() {
    activeMenuItem();
    handleLogout();

    if (document.querySelector('.STATS')) {
        handleStatsMenu();
        handleChart();
        handleStats();
    }

    if (document.querySelector('.PRODUCTS')) {
        closeAndOpenProductForm();
        loadProducts();
    }

    if (document.querySelector('.ORDERS')) {
        orderfunc();
    }

    if (document.querySelector('.CUSTOMERS')) {
        customerfunc();
    }

    if (document.querySelector('.EMPLOYEES')) {
        Employee();
    }

    if (document.querySelector('.SUPPLIERS')) {
        Supplier_PageEvent();
    }
}