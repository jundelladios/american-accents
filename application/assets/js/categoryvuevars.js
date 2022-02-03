var orderBy = [
    {
        value: "product_method_combination_name ASC",
        text: "A - Z Product Name"
    },
    {
        value: "product_method_combination_name DESC",
        text: "Z - A Product Name"
    },
    {
        value: "pricing_data_value.value DESC",
        text: "Highest to Lowest Price"
    },
    {
        value: "pricing_data_value.value ASC",
        text: "Lowest to Highest Price"
    },
    {
        value: "product_size_details DESC",
        text: "Highest to Lowest Size"
    },
    {
        value: "product_size_details ASC",
        text: "Lowest to Highest Size"
    },
];


var htmlTemplate = /*html*/`
    <div class="vue-template-wrap f-inventory-category-template mb-150">

        <v-img :img="getApiRequest.category_banner" :alt="getApiRequest.cat_name" :nofallback="true" :fallback="getImageFallback.banner" class="mb4 full-width" :width="1903" :height="643" />

        <div class="container" id="topcategory">
            <div class="row">

                <div class="mt-3 col-md-12 d-flex justify-content-between">
                    <ul class="f-breadcrumb list-unstyled d-flex m-0">
                        <li><a :href="jsvars.baseURL"><i class="icon-home icon"></i>Home</a></li>
                        <li><span>{{getApiRequest.cat_name}}</span></li>
                    </ul>
                </div>
                
                <div class="col-md-12">
                    <hr>
                </div>

                

                <div class="col-md-12 mb-4 mt-1 category-filter-sort">
                    <div class="row">
                        <div class="col-sm-3 filters-material">
                            Filters: 

                            <span v-if="getSelectedFilters.subcategories && getSelectedFilters.subcategories.length">
                                <span 
                                v-for="(subcat, index) in getSelectedFilters.subcategories" 
                                :key="'subcat-filter-' + index" 
                                class="filter-badge">{{subcat.sub_name}} <span @click="uncheckSubcategory(subcat)" class="icon icon-close-circle"></span>
                                </span>
                            </span>

                            <span v-if="getSelectedFilters.materials && getSelectedFilters.materials.length">
                                <span 
                                v-for="(material, index) in getSelectedFilters.materials" 
                                :key="'material-filter-' + index" 
                                class="filter-badge">{{material.material_type}} <span @click="uncheckMaterialFilter(material)" class="icon icon-close-circle"></span>
                                </span>
                            </span>

                            <span v-if="getSelectedFilters.sizes && getSelectedFilters.sizes.length">
                                <span 
                                v-for="(size, index) in getSelectedFilters.sizes" 
                                :key="'size-filter-' + index" 
                                class="filter-badge">{{size.product_size_details}} <span @click="unchecSizeFilter(size)" class="icon icon-close-circle"></span>
                                </span>
                            </span>

                            <span v-if="getSelectedFilters.thickness && getSelectedFilters.thickness.length">
                                <span 
                                v-for="(thick, index) in getSelectedFilters.thickness" 
                                :key="'size-filter-' + index" 
                                class="filter-badge">{{thick.product_tickness_details}} <span @click="unchecThicknessFilter(thick)" class="icon icon-close-circle"></span>
                                </span>
                            </span>

                            <span v-if="getSelectedFilters.methods && getSelectedFilters.methods.length">
                                <span 
                                v-for="(methods, index) in getSelectedFilters.methods" 
                                :key="'method-filter-' + index" 
                                class="filter-badge">{{methods.method_name}} {{methods.method_name2}} <span @click="unchecMethodFilter(methods)" class="icon icon-close-circle"></span>
                                </span>
                            </span>

                            <span 
                            v-if="!getSelectedFilters.subcategories.length && !getSelectedFilters.materials.length && !getSelectedFilters.sizes.length && !getSelectedFilters.thickness.length && !getSelectedFilters.methods.length"
                            >none</span>
                            <a v-else @click.stop="resetFilter" href="javascript:void(0)" rel="nofollow" class="font-small" style="white-space: nowrap;">remove all</a>

                        </div>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-4">
                                    <select class="bg-grey sortproducts" v-model="filterParams.orderBy">
                                        <option disabled selected hidden :value="null">Sort By</option>
                                        <option v-for="(sorting, index) in getSorting" :key="'sorting-' + index" :value="sorting.value">{{ sorting.text }}</option>
                                    </select>
                                </div>
                                <div v-if="products.metas.total" class="col-sm-8 text-right">
                                    <span>{{ products.metas.total }}</span> products
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-3">

                <div :class="\`col-lg-3 col-md-5 filter-sidebar \${openFilter ? 'show' : ''}\`">
                    <div class="bg-grey p-4">
                        
                        <template v-if="!filters.loading">
                            <button @click="resetFilter" class="text-uppercase filterbtn mb-4">Clear All Filters</button>
                        </template>
                        <div v-else class="skeleton grey mb-4" style="height: 40px;"></div>

                        <!-- price range filter -->
                        <div v-if="!filters.loading" class="aa-range-filter mb-5">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">filter by price:</span>
                            <div class="input-range">
                                <div class="frangevalues mb-2 mt-3 d-flex justify-content-between">
                                    <span class="min font-weight-bold f-cat-filter-color">{{ filterParams.priceMinFormatted }}</span>
                                    <span class="max font-weight-bold f-cat-filter-color ">{{ filterParams.priceMaxFormatted }}</span>
                                </div>
                                <div class="frange"></div>
                            </div>
                        </div>

                        <div v-else class="aa-range-filter mb-5">
                            <div class="skeleton grey animate mb-3" style="height: 20px; width: 50%;"></div>
                            <div>
                                <div class="mb-2 mt-1 d-flex justify-content-between">
                                    <div class="skeleton grey" style="height: 20px; width: 50px;"></div>
                                    <div class="skeleton grey" style="height: 20px; width: 50px;"></div>
                                </div>
                            </div>
                            <div class="skeleton grey" style="height: 20px;"></div>
                        </div>
                        <!-- end of price range filter -->
                        
                        <div v-if="optionalParams.subcategory" class="aa-cat-filter mb-5">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">subcategory:</span>
                            <ul class="list-unstyled mt-3 ml-2">
                                <li v-for="(sub, index) in filters.data.subcategories" :key="'subcategory-filters-' + index">
                                    <label class="checkbox-wrap">
                                        <span class="font-small f-cat-filter-color">{{ sub.sub_name }}</span>
                                        <input type="checkbox" checked disabled>
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <!-- subcategories filter -->
                        <template v-if="!subcategoriesLoading">
                        <div class="aa-cat-filter mb-5" v-if="filters.data.subcategories.length > 1">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">subcategories:</span>
                            <ul class="list-unstyled mt-3 ml-2">
                                <li v-for="(sub, index) in filters.data.subcategories" :key="'subcategory-filters-' + index">
                                    <label class="checkbox-wrap">
                                        <span class="font-small f-cat-filter-color">{{ sub.sub_name }}</span>
                                        <input type="checkbox" @change="sub.checked=!sub.checked" :checked="sub.checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        </template>
                        <div v-else class="mb-5">
                            <div class="skeleton grey mb-3" style="height: 20px; width: 60%;"></div>
                            <div class="row mt-3">
                                <div v-for="(mpreloader, index) in 6" :key="'subcategories-filter-preloader-' + index" class="col-12">
                                    <div class="skeleton grey mb-3" style="height: 20px;width:90%;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end of subcategories -->


                        <!-- materials -->
                        <template v-if="!filters.loading">
                        <div class="aa-material-filter mb-5" v-if="filters.data.material.length > 1">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">material type:</span>
                            <ul class="list-unstyled mt-3 ml-2">
                                <li v-for="(material, index) in filters.data.material" :key="'material-filter-' + index">
                                    <label class="checkbox-wrap">
                                        <span class="font-small f-cat-filter-color">{{ material.material_type }}</span>
                                        <input type="checkbox" @change="material.checked=!material.checked" :checked="material.checked" :value="material.material_type">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        </template>
                        <div v-else class="mb-5">
                            <div class="skeleton grey mb-3" style="height: 20px; width: 30%;"></div>
                            <div class="row mt-3 ml-1">
                                <div v-for="(mpreloader, index) in 6" :key="'material-filter-preloader-' + index" class="pl-1 pr-1">
                                    <div class="skeleton grey mb-3" style="height: 20px;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end of materials -->


                        <!-- size -->
                        <template v-if="!filters.loading">
                        <div class="aa-size-filter mb-4" v-if="filters.data.sizes.length > 1">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">size:</span>
                            <div class="row mt-3 ml-1">
                                <div v-for="(size, index) in filters.data.sizes" :key="'size-filter-' + index" class="col-6 pl-1 pr-1">
                                    <label class="checkbox-wrap">
                                        <span class="font-small f-cat-filter-color">{{ size.product_size_details }}</span>
                                        <input type="checkbox" @change="size.checked=!size.checked" :checked="size.checked" :value="size.product_size_details">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        </template>
                        <div v-else class="mb-5">
                            <div class="skeleton grey mb-3" style="height: 20px; width: 30%;"></div>
                            <div class="row mt-3 ml-1">
                                <div v-for="(mpreloader, index) in 6" :key="'size-filter-preloader-' + index" class="col-6 pl-1 pr-1">
                                    <div class="skeleton grey mb-3" style="height: 20px;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end of size -->


                        <!-- thickness -->
                        <template v-if="!filters.loading">
                        <div class="aa-thickness-filter mb-4" v-if="filters.data.thickness.length > 1">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">thickness:</span>
                            <div class="row mt-3 ml-1">
                                <div v-for="(thick, index) in filters.data.thickness" :key="'thick-filter-' + index" class="col-6 pl-1 pr-1">
                                    <label class="checkbox-wrap">
                                        <span class="font-small f-cat-filter-color">{{ thick.product_tickness_details }}</span>
                                        <input type="checkbox" @change="thick.checked=!thick.checked" :checked="thick.checked" :value="thick.product_tickness_details">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        </template>
                        <div v-else class="mb-5">
                            <div class="skeleton grey mb-3" style="height: 20px; width: 30%;"></div>
                            <div class="row mt-3 ml-1">
                                <div v-for="(mpreloader, index) in 6" :key="'thickness-filter-preloader-' + index" class="col-6 pl-1 pr-1">
                                    <div class="skeleton grey mb-3" style="height: 20px;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end of thickness -->

                        
                        <!-- colors -->
                        <template v-if="!colorsLoading">
                        <div class="aa-color-filter mb-4" v-if="filters.data.colors.length > 0">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">color:</span>
                            <div class="row mt-4 ml-1">
                                <template v-for="(color, index) in filters.data.colors" :key="'color-filter-' + index">
                                    <div class="col-2 pl-1 pr-1 mb-3">
                                        <label class="round-checkbox color-filter"
                                        v-tooltip:top="color.colorname"
                                        >
                                            <input type="checkbox" @change="color.checked=!color.checked" :checked="color.checked" :value="color.slug">
                                            <span v-if="!color.iscolorimage" class="checkmark" :style="'background: ' + color.colorhex + ';'"></span>
                                            <span v-else class="checkmark lazyload bg-cover" v-img:data-bgset="color.colorimageurl" :fallback="getImageFallback.normal"></span>
                                            <span class="underline"></span>
                                        </label>
                                    </div>
                                </template>
                            </div>
                        </div>
                        </template>
                        <div v-else class="mb-5">
                            <div class="skeleton grey mb-3" style="height: 20px; width: 30%;"></div>
                            <div class="row mt-3 ml-1">
                                <div v-for="(mpreloader, index) in 12" :key="'colors-filter-preloader-' + index" class="col-2 pl-1 pr-1 mb-3">
                                    <div class="skeleton grey mb-3" style="height: 23px; width:23px; border-radius: 50px;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end of colors -->


                        <!-- print methods -->
                        <template v-if="!filters.loading">
                        <div class="aa-printmethod-filter" v-if="filters.data.methods.length > 1">
                            <span class="text-uppercase font-weight-bold f-cat-filter-color">print method:</span>
                            <ul class="list-unstyled mt-3 ml-2">
                                <li v-for="(method, index) in filters.data.methods" :key="'color-filter-' + index">
                                    <label class="checkbox-wrap">
                                        <span class="font-small f-cat-filter-color">{{ method.method_name }} {{ method.method_name2 }}</span>
                                        <input type="checkbox" :value="method.method_slug" :checked="method.checked" :value="method.method_slug" @change="method.checked=!method.checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        </template>
                        <div v-else>
                            <div class="skeleton grey mb-3" style="height: 20px; width: 30%;"></div>
                            <div class="row mt-3">
                                <div v-for="(mpreloader, index) in 3" :key="'pmethod-filter-preloader-' + index" class="col-12">
                                    <div class="skeleton grey mb-3" style="height: 20px;width:80%;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end of print methods -->
            

                    </div>
                </div>
                
                <!--
                <div class="col-lg-3 col-md-5 filter-sidebar preloader" v-else>
                    <div class="skeleton animation" style="height: 800px;"></div>
                </div>
                -->


                <div class="col-lg-9 col-md-12">

                    <div style="min-height: 800px;" v-if="products.loading">
                        <div class="ajax-loader">
                            <div class="background"></div>
                            <div class="loaderWrap">
                                <div>
                                    <div class="loader"></div>    
                                    <div class="text">Loading</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="row famericanaccentsproducts">
                        <div v-for="(product, index) in getProductsIterate" :key="'product-index-' + product.hid" class="col-lg-4 col-md-4 col-sm-6 productcol" :data-product-id="product.hid">
                            <product-component :product="product" />
                        </div>
                    </div>

                    <div v-if="!filters.loading && !products.loading && !getProductsIterate.length" class="row">
                        <div class="col-md-12">
                            <div class="pt-5 pb-5">
                                <p class="text-center">There is no product available.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="products.metas.total > apiParams.paginate" class="fpagination">
                        <vue-paginate 
                        v-model="filterParams.page"
                        :page-count="getPaginationCount"
                        :page-range="10"
                        :click-handler="paginateProducts"
                        :container-class="'pagination'"
                        :page-class="'page-item'"
                        :first-last-button="true"
                        :prev-text="paginationArrows.prev"
                        :next-text="paginationArrows.next"
                        :last-button-text="paginationArrows.last"
                        :first-button-text="paginationArrows.first"
                        />
                    </div>

                </div>
            </div>


        </div>

        <button v-if="!openFilter" @click.stop="openFilter=!openFilter" class="text-uppercase floating-filterbtn mb-4 btn btn-primary">
            <span v-if="!openFilter">Filter Products</span>
            <span v-else>Close Filter</span>
        </button>

    </div>
`;