
<div v-if="stockshape.input.product_print_method_id" class="aa-popup" :data-print-methodid="stockshape.input.product_print_method_id">

<div class="aa-content-wrap">
    <div class="aa-content md">
        <a href="javascript:void(0)" @click.prevent="resetStockShapesInputs({product_print_method_id: null, index: null})" class="aa-close">&times;</a>
        
        <h1 class="wp-heading-inline">
            <span>Stock Shapes for<br>
            
            <strong v-if="stockshapeselectedcombo" :style="`color:${stockshapeselectedcombo.productline.printmethod.method_hex};`">
                <span v-if="stockshapeselectedcombo.allow_print_method_prefix">{{stockshapeselectedcombo.productline.printmethod.method_prefix}}</span><span>{{stockshapeselectedcombo.product.product_name}}</span>
            </strong>
            
            ({{ stockshape.pagination.metas.total }})

            </span>
            <strong v-if="stockshape.input.index!=null" class="d-block">Editing for {{ stockshape.input.code }}-{{ stockshape.input.stockname }}</strong>
        </h1>

        <small class="d-block mb-2">Note: product combo stock shape without images will be ignored in product display.</small>

        <!-- form here -->

        <div v-if="pstockshapestate!='lists'" class="mt-4 mb-4 row">
            <div class="col-md-12">
                <form @submit.prevent="saveProductStockShape" data-vv-scope="productstockshape">

                    <div class="mb-2">
                        <label class="d-block mb-2" for="stockname">Stock Shape Name:</label>
                        <input type="text" v-model="stockshape.input.stockname" v-validate="'required'" data-vv-as="product stock shape name" name="stockname">
                        <span class="v-error">{{errors.first('productstockshape.stockname')}}</span>
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2" for="code">Code:</label>
                        <input type="text" v-model="stockshape.input.code" v-validate="'required'" data-vv-as="product stock shape code" name="code">
                        <span class="v-error">{{errors.first('productstockshape.code')}}</span>
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">Priority #</label>
                        <input type="number" v-model="stockshape.input.priority">
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">Stock (0 if out of stock)</label>
                        <input type="number" v-model="stockshape.input.in_stock">
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">VDS SAGE PRODUCT ID</label>
                        <input type="text" v-model="stockshape.input.vdsproductid">
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">VDS SAGE ITEM #</label>
                        <input type="text" v-model="stockshape.input.vdsid">
                    </div>

                    <hr class="mt-5">

                    <div class="mb-2">
                        <strong class="d-block">Main Gallery Images:</strong>
                        <p>Note: Empty image will automatically removed after submission, You can drag and drop product images.</p>
                        <p v-if="!stockshape.input.hid">
                            <label>
                                <input type="checkbox" v-model="stockshape.input.autoassignimg" :true-value="1" :false-value="null"> auto-assign main gallery images? 
                            </label>
                        </p>
                    </div>

                    <div class="mb-3">
                        <button type="button" @click="pstockshape_toggleSelectKey('image', true)" class="button mr-2">Select All Images</button>
                        <button type="button" @click="pstockshape_toggleSelectKey('image', false)" class="button mr-2">Unselect Images</button>
                        <button type="button" @click="pstockshape_removeCheckedItems_('image', 'Are you sure you want to remove selected images?')" v-if="pstockshape_getIsSelectedImage.length" class="button mr-2">Remove {{pstockshape_getIsSelectedImage.length}} Images</button>
                        <button v-if="stockshape.input.code" type="button" @click="pullAnimatedMediasStockShape({
                            id: stockshapeselectedcombo.hid,
                            code: stockshape.input.code,
                            type: 'main'
                        })" class="button mr-2">Pull animated medias</button>
                    </div>

                    <div v-if="stockshape.input.image && !stockshape.input.autoassignimg" class="mb-2 col-md-12">
                        <draggable 
                        v-model="stockshape.input.image" 
                        class="v-draggable"
                        tag="div" 
                        v-bind="vueDragOptions"
                        @start="stockshapeDrag.imagedrag = true"
                        @end="stockshapeDrag.imagedrag = false"
                        >
                            <transition-group type="transition" tag="div" class="row" :name="!stockshapeDrag.imagedrag ? 'flip-list' : null">
                                <div v-for="(pstockimg, index) in stockshape.input.image" :key="`carousel-item-inputs-${index}`" class="p-1 col-xl-4 col-lg-12 mb-4">

                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(stockshape.input.image, index, {...pstockimg, _isSelected: !pstockimg._isSelected})" 
                                        :checked="pstockimg._isSelected">
                                    </div>

                                    <a href="javascript:void(0)" @click.stop="stockshapechoosecolorimage(index)" class="d-block link-img img-form-wrap mb-2">
                                        <template v-if="!stockshape?.input?.image?.[index]?.image">
                                            <img src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                                        </template>
                                        <template v-else>
                                            <template v-if="!pstockimg?.type">
                                                <img :src="stockshape.input.image[index].image" alt="" class="full-width">
                                            </template>
                                            <template v-else>
                                                <img v-if="pstockimg?.type != 'html'" :src="stockshape.input.image[index].image" alt="" class="full-width">
                                                <div v-if="pstockimg?.type == 'html'" class="anim-thumbnail">
                                                    <iframe :src="stockshape.input.image[index].image" class="full-height full-width" scrolling="no"></iframe>
                                                    <p class="text-center m-0">Click here to choose media</p>
                                                </div>
                                            </template>
                                        </template>
                                    </a>

                                    <div class="mb-2">
                                        <input type="text" v-model="stockshape.input.image[index].title" placeholder="Enter Title" class="full-width" />
                                    </div>

                                    <div class="mb-2">
                                        <input type="number" v-model="stockshape.input.image[index].top" placeholder="Top Position  (%)" class="full-width" />
                                    </div>

                                    <a href="javascript:void(0)" @click.stop="stockshape.input.image.splice(index, 1);" class="button mt-2">remove</a>
                                </div>
                            </transition-group>
                        </draggable>
                    </div>

                    <div v-if="!stockshape.input.autoassignimg" class="mb-2">
                        <a href="javascript:void(0)" 
                        @click.stop="stockshapeaddcolorimage" 
                        class="button mb-2 button-secondary">Add Image</a>
                    </div>

                    <hr class="mt-5">

                    <div class="mb-2">
                        <strong class="d-block">TEMPLATES:</strong>
                        <p>Note: Empty Template will automatically remove after submission, You can drag and drop product templates.</p>
                    </div>

                    <div class="mb-3">
                        <button type="button" @click="pstockshape_toggleSelectKey('templates', true)" class="button mr-2">Select All Templates</button>
                        <button type="button" @click="pstockshape_toggleSelectKey('templates', false)" class="button mr-2">Unselect Templates</button>
                        <button type="button" @click="pstockshape_removeCheckedItems_('templates', 'Are you sure you want to remove selected templates?')" v-if="pstockshape_getIsSelectedTemplates.length" class="button mr-2">Remove {{pstockshape_getIsSelectedTemplates.length}} Templates</button>
                    </div>

                    <draggable 
                    v-model="stockshape.input.templates" 
                    class="v-draggable"
                    tag="div" 
                    v-bind="vueDragOptions"
                    @start="stockshapeDrag.templatedrag = true"
                    @end="stockshapeDrag.templatedrag = false"
                    >
                        <transition-group type="transition" tag="div" class="mb-2 row col-md-12" :name="!stockshapeDrag.templatedrag ? 'flip-list' : null">
                            <div class="col-xl-4 col-lg-12 col-sm-12 p-3 mb-3 v-drag-item" v-for="(dl, index) in stockshape.input.templates" :key="`download-${index}`">
                                <div class="pt-5 pb-3" style="border: 1px solid rgb(195, 195, 195);border-radius: 5px;position:relative;">

                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(stockshape.input.templates, index, {...dl, _isSelected: !dl._isSelected})" 
                                        :checked="dl._isSelected">
                                    </div>

                                    <div v-if="dl.preview" class="col-md-12 text-center">
                                        <a class="mb-2" :href="dl.preview" target="_blank">
                                            <div style="width: 100%; height: 230px;" class="d-flex justify-content-center align-items-center">
                                                <img :src="dl.preview" class="full-width img-r">
                                            </div>
                                        </a>
                                    
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" readonly class="full-width" v-model="dl.link" placeholder="Document Link">
                                        <a class="button mt-2 mr-2" href="javascript:void(0)" @click.stop="stockshapeselectTemplateLink(index)">select</a>
                                        <a class="button mt-2 button-danger" href="javascript:void(0)" @click.stop="stockshape.input.templates.splice(index, 1)">remove</a>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </draggable>

                    <div class="mb-2">
                        <a class="button button-secondary" @click.stop="stockshapeaddTemplates">Add Template</a>
                    </div>


                    <hr class="mt-5">


                    <div class="mb-2">
                        <strong class="d-block">IDEA GALLERIES:</strong>
                        <p>Note: Empty idea gallery images will automatically be removed after submission, You can drag and drop product idea galleries images.</p>
                        <p v-if="!stockshape.input.hid">
                            <label>
                                <input type="checkbox" v-model="stockshape.input.autoassignidea" :true-value="1" :false-value="null"> auto-assign idea gallery images? 
                            </label>
                        </p>
                    </div>

                    <div class="mb-3">
                        <button type="button" @click="pstockshape_toggleSelectKey('idea_galleries', true)" class="button mr-2">Select All Idea Gallery</button>
                        <button type="button" @click="pstockshape_toggleSelectKey('idea_galleries', false)" class="button mr-2">Unselect Idea Gallery</button>
                        <button type="button" @click="pstockshape_removeCheckedItems_('idea_galleries', 'Are you sure you want to remove selected idea gallery?')" v-if="pstockshape_getIsSelectedIdeaGallery.length" class="button mr-2">Remove {{pstockshape_getIsSelectedIdeaGallery.length}} Idea Gallery</button>
                        <button v-if="stockshape.input.code" type="button" @click="pullAnimatedMediasStockShape({
                            id: stockshapeselectedcombo.hid,
                            code: stockshape.input.code,
                            type: 'idg'
                        })" class="button mr-2">Pull animated medias</button>
                    </div>

                    <div v-if="stockshape.input.idea_galleries && !stockshape.input.autoassignidea" class="mb-2 col-md-12">
                        <draggable 
                        v-model="stockshape.input.idea_galleries" 
                        class="v-draggable"
                        tag="div" 
                        v-bind="vueDragOptions"
                        @start="stockshapeDrag.ideagallerydrag = true"
                        @end="stockshapeDrag.ideagallerydrag = false"
                        >
                            <transition-group type="transition" tag="div" class="row" :name="!stockshapeDrag.ideagallerydrag ? 'flip-list' : null">
                                <div v-for="(pstockimg, index) in stockshape.input.idea_galleries" :key="`carousel-item-inputs-${index}`" class="p-1 col-xl-4 col-lg-12 mb-4">
                                    
                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(stockshape.input.idea_galleries, index, {...pstockimg, _isSelected: !pstockimg._isSelected})" 
                                        :checked="pstockimg._isSelected">
                                    </div>

                                    <a href="javascript:void(0)" @click.stop="stockshapechooseideagalleryimage(index)" class="d-block link-img img-form-wrap mb-2">
                                        <template v-if="!stockshape?.input?.idea_galleries?.[index]?.image">
                                            <img src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                                        </template>
                                        <template v-else>
                                            <template v-if="!pstockimg?.type">
                                                <img :src="stockshape.input.idea_galleries[index].image" alt="" class="full-width">
                                            </template>
                                            <template v-else>
                                                <img v-if="pstockimg?.type != 'html'" :src="stockshape.input.idea_galleries[index].image" alt="" class="full-width">
                                                <div v-if="pstockimg?.type == 'html'" class="anim-thumbnail">
                                                    <iframe :src="stockshape.input.idea_galleries[index].image" class="full-height full-width" scrolling="no"></iframe>
                                                    <p class="text-center m-0">Click here to choose media</p>
                                                </div>
                                            </template>
                                        </template>
                                    </a>

                                    <div v-if="!stockshape.input.idea_galleries[index].usecurfile" class="mb-2">
                                        <input type="text" readonly placeholder="Download Link URL" v-model="stockshape.input.idea_galleries[index].downloadLink" class="full-width mb-2">
                                        <a href="javascript:void(0)" @click.stop="stockshapeideagallerydownloadfileselect(index)" class="button mb-2">Select File</a>
                                    </div>

                                    <label for="per_thousand" class="mb-2 d-block"> 
                                    <input 
                                    v-model="stockshape.input.idea_galleries[index].usecurfile" 
                                    type="checkbox" 
                                    value="per_thousand"
                                    :true-value="1"
                                    :false-value="0">
                                    Use current photo for the download link url.</label>

                                    <div class="mb-2">
                                        <input type="text" v-model="stockshape.input.idea_galleries[index].text" placeholder="Enter Title" class="full-width" />
                                    </div>

                                    <div class="mb-2">
                                        <input type="number" v-model="stockshape.input.idea_galleries[index].top" placeholder="Top Position  (%)" class="full-width" />
                                    </div>

                                    <a href="javascript:void(0)" @click.stop="stockshape.input.idea_galleries.splice(index, 1);" class="button mt-2">remove</a>
                                </div>
                            </transition-group>
                        </draggable>
                    </div>

                    <div class="mb-2" v-if="!stockshape.input.autoassignidea">
                        <a class="button button-secondary" @click.stop="stockshapeaddIdeaGallery">Add Idea Gallery Image</a>
                    </div>

                    <hr class="mt-5">


                    <div class="mt-5 mt-3 floating-button-save">
                        <button v-if="stockshape.input.id==null" type="submit" class="button button-primary">Add Stock Shape</button>
                        <button v-else type="submit" class="button button-primary">Save Stock Shape</button>
                        <a href="javascript: void(0)" class="button" @click.stop="resetStockShapesInputs({product_print_method_id:stockshape.input.product_print_method_id, index: null}); pstockshapestate='lists'">View Stock Shapes</a>
                    </div>

                </form>
            </div>

        </div>





        <!-- lists here. -->


        <div v-else>
            
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <a href="javascript:void(0)" @click.stop="resetStockShapesInputs({product_print_method_id:stockshape.input.product_print_method_id, index: null}); pstockshapestate='form'" class="button">Add Stock Shape</a>
                </div>
                <div class="col-md-6" v-if="!stockshapecollections.loading">
                    <div v-if="stockshapecollections.data.length" class="d-flex">
                        Generate stock shape from stock shape collection: 
                        <select class="ml-1" v-model="stockshapegenerateCollection">
                            <option v-for="ccollection in stockshapecollections.data" :value="ccollection.hid">{{ ccollection.title }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6" v-else>
                    Loading...
                </div>
            </div>

            <div class="mb-3">
                <button type="button" @click="selectAll_stockshape_" class="button mr-2">Select All</button>
                <button type="button" @click="unselectAll_stockshape_" class="button mr-2">Unselect</button>
                <button type="button" @click="removeCheckedItems_stockshape_" v-if="getIsSelectedStockshape.length" class="button mr-2">Remove ({{getIsSelectedStockshape.length}})</button>
            </div>

            <div class="mb-3">
                <form @submit.prevent="searchEntryStockshapeQueryFilter" style="text-align:right;">
                    <input placeholder="Search" type="text" v-model="stockshape.query" />
                </form>
            </div>

            <div class="row" v-if="stockshape.loading">
                <div class="col-md-12">
                    <p style="text-align: center;">Loading..</p>
                </div>
            </div>

            <div class="row" v-else>
                <div v-if="!stockshape.data.length" class="col-md-12">
                    <p style="text-align: center;">There is no product stock shape.</p>
                </div>

                <div v-else class="col-md-12 mt-3 row">

                    <div v-for="(pshape, index) in stockshape.data" :key="`pshape-data-${index}`" class="col-md-4">
                        <div class="mb-4 p-2" style="border: 1px solid #e0e0e0;">

                            <div style="float:right;">
                                <input type="checkbox" 
                                @input="() => $set(stockshape.data, index, {...pshape, _isSelected: !pshape._isSelected})" 
                                :checked="pshape._isSelected">
                            </div>

                            <div class="d-block mb-2">
                                <h4>{{ pshape.stockname }} - {{ pshape.code }}</h4>
                            </div>
                            <div class="d-block mb-2">Priority #: {{ pshape.priority }}</div>
                            <div class="d-block mb-2">Images: {{ pshape.imagedata.length }}</div>
                            <div class="d-block mb-2">Templates: {{ pshape.counttemplates }}</div>
                            <div class="d-block mb-2">VDS SAGE PRODUCT ID: {{ pshape.vdsproductid }}</div>
                            <div class="d-block mb-2">VDS SAGE ITEM #: {{ pshape.vdsid }}</div>
                            <div class="d-block mb-2">STOCK: {{ pshape.in_stock }} 
                                <span v-if="pshape.in_stock>0" style="color:green;font-weight:bold">(In Stock)</span>
                                <span v-else style="color:red;font-weight:bold">(Out of Stock)</span>
                            </div>

                            <div class="d-block mt-3 mb-2">

                                <a href="javascript: void(0)" @click.stop="resetStockShapesInputs({
                                    ...pshape,
                                    id: pshape.hid,
                                    product_print_method_id:stockshape.input.product_print_method_id, 
                                    index: index,
                                    image: [...pshape.imagedata],
                                    idea_galleries: [...pshape.ideagallerydata],
                                    templates: [...pshape.templatedata]
                                }); pstockshapestate='form';" class="button">Edit</a>

                                <a href="javascript: void(0)" @click.stop="removeproductStockShape(pshape.hid, index)" class="button">Remove</a>
                            </div>
                        </div>
                    </div>

                    <p v-if="stockshape.pagination.metas.next_page_url" style="text-align: center;width:100%;">
                        <button v-if="!stockshape.loadingNext" @click="getStockshapeNextButton" class="button primary">Load More</button>
                        <span v-else>Loading...</span>
                    </p>

                </div>
                
            </div>

        </div>

        
    </div>
</div>

</div>