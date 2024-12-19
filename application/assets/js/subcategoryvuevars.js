

// ================ HTML MAIN TEMPLATE ============== //
var mainTemplate = /*html */`
<div class="container">
    <div class="row">
        <div class="mt-3 col-md-12">
            <ul class="f-breadcrumb list-unstyled d-flex m-0">
                <li><a :href="jsvars.baseURL"><i class="icon-home icon"></i>Home</a></li>
                <li><a :href="jsvars.baseURL + 'product/' + plineVar.cat_slug ">{{ plineVar.cat_name }}</a></li>
                <li>
                    <span v-if="plineVar.sub_name_alt" v-html="plineVar.sub_name_alt"></span>
                    <span v-else>{{ plineVar.sub_name }}</span>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <hr>
        </div>
    </div>
</div>

<div class="container" id="topHeaderScroll">
    <div class="row">
        <div class="col-md-12 mt-3 mb-3">
            <h1 v-if="plineVar.sub_name_alt" class="subcategorytitle text-center text-uppercase" v-html="plineVar.sub_name_alt"></h1>
            <h1 v-else class="subcategorytitle text-center text-uppercase">{{ plineVar.sub_name }}</h1>
            <h4 class="text-center subcategorysubtitle">Select Your Print Method:</h4>
        </div>
    </div>
</div>
`;





// ============= HTML TEMPLATE ================//
var htmlTemplate = ``;


htmlTemplate += /*html */`
<div>`;

htmlTemplate += mainTemplate;

htmlTemplate += desktopTemplate;

htmlTemplate += mobileTemplate;
    
htmlTemplate += /*html*/ `</div>
`;