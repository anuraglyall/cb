<?php
error_reporting(-1);
$servername = "localhost"; // Replace with your server name
$username = "u110612666_cmsdiamond"; // Replace with your MySQL username
$password = "Mercury01#"; // Replace with your MySQL password
$database = "u110612666_cmsdiamond"; // Replace with your MySQL database name

// Create a connection
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Use the connection for your database operations

// Close the connection
$mysqli->close();
// Assuming you have established a MySQL connection

$html = '<ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="https://cms.designanddevelopment.in/dashboard">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-widgets">Dashboards</span>
                            </a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="https://cms.designanddevelopment.in/#sidebarApps" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApps">
                                <i class="ri-apps-2-line"></i> <span data-key="t-apps">Styles</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarApps">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/add-new-style" class="nav-link" data-key="t-calendar"> Add New Style</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/list-of-style" class="nav-link" data-key="t-calendar"> List of Styles</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/style-archive" class="nav-link" data-key="t-calendar"> Style Archive</a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </li>                        
                        <li class="nav-item">
                                                                
                                    
                            
                            <a class="nav-link menu-link" href="https://cms.designanddevelopment.in/#sidebarmaster" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApps">
                                <i class="ri-layout-3-line"></i> <span data-key="t-apps">Masters</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarmaster">
                                <ul class="nav nav-sm flex-column">
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/customer" class="nav-link" data-key="t-calendar">Customer</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/partners" class="nav-link" data-key="t-calendar">Vendor</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/category" class="nav-link" data-key="t-calendar">Category</a>
                                    </li>
                                    
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/sub-category" class="nav-link" data-key="t-calendar">Sub Category</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/metals" class="nav-link" data-key="t-calendar">Metals</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/metal-purity" class="nav-link" data-key="t-calendar">Metals Purity</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/mfinishes" class="nav-link" data-key="t-calendar">Metal Finishes</a>
                                    </li>
                                    
                                   <li class="nav-item">
                                        <a href="#sidebarEmail" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-email">
                                            Diamonds
                                        </a>
                                        <div class="menu-dropdown collapse" id="sidebarEmail" style="">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="https://cms.designanddevelopment.in/diam-master" class="nav-link" data-key="t-mailbox"> Add Diamonds </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#sidebaremailTemplates" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebaremailTemplates" data-key="t-email-templates">
                                                        Masters
                                                    </a>
                                                    <div class="collapse menu-dropdown" id="sidebaremailTemplates">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/diamonds-cut" class="nav-link" data-key="t-basic-action">Diamond Cut</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/diamonds-shape"  class="nav-link" data-key="t-ecommerce-action">Diamond Shape</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/diamonds-color"  class="nav-link" data-key="t-ecommerce-action">Diamond Color</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/diamonds-clarity"  class="nav-link" data-key="t-ecommerce-action">Diamond Clarity</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/diamonds-pointers"  class="nav-link" data-key="t-ecommerce-action">Diamond Pointers</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/diamonds-sieve-size"  class="nav-link" data-key="t-ecommerce-action">Diamond Sieve Size</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/diamonds-unit"  class="nav-link" data-key="t-ecommerce-action">Diamond Unit</a>
                                                            </li>
                                                            
                                                            
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                   <li class="nav-item">
                                        <a href="#sidebarEmail2" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail2" data-key="t-email">
                                            Gemstones
                                        </a>
                                        <div class="menu-dropdown collapse" id="sidebarEmail2" style="">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="https://cms.designanddevelopment.in/diam-gemstone" class="nav-link" data-key="t-mailbox"> Add Gemstone </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#sidebaremailTemplates2" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebaremailTemplates2" data-key="t-email-templates">
                                                        Masters
                                                    </a>
                                                    <div class="collapse menu-dropdown" id="sidebaremailTemplates2">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/gemstone-type"  class="nav-link" data-key="t-ecommerce-action">Gemstone Type</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/gemstone-cut"  class="nav-link" data-key="t-ecommerce-action">Gemstone Cut</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/gemstone-shape"  class="nav-link" data-key="t-ecommerce-action">Gemstone Shape</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/gemstone-quality"  class="nav-link" data-key="t-ecommerce-action">Gemstone Quality</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/gemstone-size"  class="nav-link" data-key="t-ecommerce-action">Gemstone Size</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/gemstone-origin"  class="nav-link" data-key="t-ecommerce-action">Gemstone Origin</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/gemstone-unit"  class="nav-link" data-key="t-ecommerce-action">Gemstone Unit</a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                   <li class="nav-item">
                                        <a href="#sidebarEmail3" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail2" data-key="t-email">
                                            Pearls
                                        </a>
                                        <div class="menu-dropdown collapse" id="sidebarEmail3" style="">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="https://cms.designanddevelopment.in/diam-pearl" class="nav-link" data-key="t-mailbox"> Add Perms </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#sidebaremailTemplates2" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebaremailTemplates2" data-key="t-email-templates">
                                                        Masters
                                                    </a>
                                                    <div class="collapse menu-dropdown" id="sidebaremailTemplates2">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/pearl-type"  class="nav-link" data-key="t-ecommerce-action">Pearl Type</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/pearl-shape"  class="nav-link" data-key="t-ecommerce-action">Pearl Shape</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/pearl-color"  class="nav-link" data-key="t-ecommerce-action">Pearl Color</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/pearl-size"  class="nav-link" data-key="t-ecommerce-action">Pearl Size</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/pearl-unit"  class="nav-link" data-key="t-ecommerce-action">Pearl Unit</a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                   <li class="nav-item">
                                        <a href="#sidebarEmail4" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail4" data-key="t-email">
                                            Dimensions
                                        </a>
                                        <div class="menu-dropdown collapse" id="sidebarEmail4" style="">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="https://cms.designanddevelopment.in/diam-dimensions" class="nav-link" data-key="t-mailbox"> Add Dimensions </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#sidebaremailTemplates2" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebaremailTemplates2" data-key="t-email-templates">
                                                        Masters
                                                    </a>
                                                    <div class="collapse menu-dropdown" id="sidebaremailTemplates2">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/dimensions-unit"  class="nav-link" data-key="t-ecommerce-action">Dimension unit</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="https://cms.designanddevelopment.in/dimensions-country"  class="nav-link" data-key="t-ecommerce-action">Dimension Country</a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                   <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/currency" class="nav-link" data-key="t-calendar">Currency</a>
                                    </li> 
                                   <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/prices" class="nav-link" data-key="t-calendar">Prices</a>
                                    </li> 
                                   <li class="nav-item">
                                        <a href="https://cms.designanddevelopment.in/raw-material-issue-receive" class="nav-link" data-key="t-calendar">Raw Material Issue/Receive</a>
                                    </li> 
                                </ul>
                            </div>
                        </li>
                        

//                    </ul>'; // The HTML code provided in your question
//echo $html;
//
//exit;
$dom = new DOMDocument();
$dom->loadHTML($html);

$xpath = new DOMXPath($dom);

$csvData = array();

// Recursive function to parse the menu HTML structure
function parseMenuItems($xpath, $items, $level, $parentId = null, $path = '')
{
    global $csvData;

    foreach ($items as $item) {
        $name = trim($item->textContent);
        $csvData[] = array($name, $level, $parentId, $path);

        // Insert into the MySQL table
        $query = "INSERT INTO menu_data (name, level, parent_id, path) VALUES ('$name', '$level', '$parentId', '$path')";
        $mysqli->query($query);
        
        $itemId = $mysqli->insert_id;

        // Parse submenu items recursively
        $submenuItems = $xpath->query("./following-sibling::div[@class='collapse menu-dropdown']/ul[@class='nav nav-sm flex-column']/li[@class='nav-item']/a[@class='nav-link']", $item);
        if ($submenuItems->length > 0) {
            parseMenuItems($xpath, $submenuItems, $level + 1, $itemId, $path . '|' . $itemId);
        }
    }
}  

$menuItems = $xpath->query("//ul[@id='navbar-nav']/li[@class='nav-item']/a[@class='nav-link menu-link']");
parseMenuItems($xpath, $menuItems, 1);

// Close the MySQL connection
$mysqli->close();

// Export data to CSV file
$filename = "/public_html/cms/menu_data.csv";
$file = fopen($filename, 'w');

foreach ($csvData as $row) {
    fputcsv($file, $row);
}

fclose($file);

echo "Data has been exported to $filename.";
?>