<div v-if="pcolorstockshape.input.product_print_method_id" class="aa-popup" :data-print-methodid="pcolorstockshape.input.product_print_method_id">

<div class="aa-content-wrap">
    <div class="aa-content md">
        
        <a href="javascript:void(0)" @click.prevent="resetColorsStockshapeInputs({product_print_method_id: null, index: null})" class="aa-close">&times;</a>

        <h1 class="wp-heading-inline">
            <span>Color + Stockshape for<br>
            
            <strong v-if="pcolorstockselectedcombo" :style="`color:${pcolorstockselectedcombo.productline.printmethod.method_hex};`">
                <span v-if="pcolorstockselectedcombo.allow_print_method_prefix">{{pcolorstockselectedcombo.productline.printmethod.method_prefix}}</span><span>{{pcolorstockselectedcombo.product.product_name}}</span>
            </strong>

            ({{ pcolorstockshape.pagination.metas.total }})

            </span>
            <strong v-if="pcolorstockshape.input.index!=null && stcclr_selectedColor && stcclr_selectedStockShape" class="d-block">
                Editing for {{stcclr_selectedColor.colorname}} - {{stcclr_selectedStockShape.code}}
            </strong>
        </h1>

        <small class="d-block mb-2">Note: product combo colors without images will be ignored in product display.</small>

        <!-- form here -->

        <div v-if="pcolorstockshapestate!='lists'" class="mt-4 mb-4 row">
            <div class="col-md-12">
                <form @submit.prevent="stcclr_saveProductColorStockshape" data-vv-scope="productcolorstockshape">

                    <div v-if="!pcolorstockshape.colors.loading" class="mb-2">
                        <label class="d-block mb-2">Select Color:</label>
                        <select v-model="pcolorstockshape.input.product_color_id"
                        v-validate="'required'"
                        name="color"
                        data-vv-as="product color"
                        >
                            <option v-for="(clr, index) in pcolorstockshape.colors.data" :value="clr.hid">{{clr.colorname}}</option>
                        </select>
                        <p v-if="!pcolorstockshape.colors.data.length">Please add a color.</p>
                        <span class="v-error">{{errors.first('productcolorstockshape.color')}}</span>
                    </div>

                    <div v-if="!pcolorstockshape.stockshape.loading" class="mb-2">
                        <label class="d-block mb-2">Select Stockshape:</label>
                        <select v-model="pcolorstockshape.input.product_stockshape_id"
                        v-validate="'required'"
                        name="stockshape"
                        data-vv-as="product stockshape"
                        >
                            <option v-for="(stc, index) in pcolorstockshape.stockshape.data" :value="stc.hid">{{stc.code}}</option>
                        </select>
                        <p v-if="!pcolorstockshape.stockshape.data.length">Please add a stockshape.</p>
                        <span class="v-error">{{errors.first('productcolorstockshape.stockshape')}}</span>
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">Priority #</label>
                        <input type="number" v-model="pcolorstockshape.input.priority">
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">VDS SAGE PRODUCT ID</label>
                        <input type="text" v-model="pcolorstockshape.input.vdsproductid">
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">VDS SAGE ITEM #</label>
                        <input type="text" v-model="pcolorstockshape.input.vdsid">
                    </div>

                    <hr class="mt-5">

                    <div>

                        <div class="mb-2">
                            <strong class="d-block">Main Gallery Images:</strong>
                            <p>Note: Empty image will automatically be removed after submission. You can drag and drop product main gallery images.</p>
                            <p v-if="!pcolorstockshape.input.hid">
                                <label>
                                    <input type="checkbox" v-model="pcolorstockshape.input.autoassignimg" :true-value="1" :false-value="null"> auto-assign main gallery images? 
                                </label>
                            </p>
                        </div>

                        <div class="mb-3">
                            <button type="button" @click="pcolorstockshape_toggleSelectKey('image', true)" class="button mr-2">Select All Images</button>
                            <button type="button" @click="pcolorstockshape_toggleSelectKey('image', false)" class="button mr-2">Unselect Images</button>
                            <button type="button" @click="pcolorstockshape_removeCheckedItems_('image', 'Are you sure you want to remove selected images?')" v-if="pcolorstockshape_getIsSelectedImage.length" class="button mr-2">Remove {{pcolorstockshape_getIsSelectedImage.length}} Images</button>
                            <button v-if="stcclr_selectedColor && stcclr_selectedStockShape && stcclr_selectedColor.colorname && stcclr_selectedStockShape.code" type="button" @click="pullAnimatedMediasColorsStockShape({
                                id: pcolorstockselectedcombo.hid,
                                color: stcclr_selectedColor.colorname,
                                code: stcclr_selectedStockShape.code,
                                type: 'main'
                            })" class="button mr-2">Pull animated medias</button>
                        </div>

                        <!-- <div class="mb-2">
                            <label>Image count: {{ pcolors.input.image.length }}</label>
                        </div> -->

                        <div v-if="pcolorstockshape.input.image && !pcolorstockshape.input.autoassignimg" class="mb-2 col-md-12">
                            <draggable 
                            v-model="pcolorstockshape.input.image" 
                            class="v-draggable"
                            tag="div" 
                            v-bind="vueDragOptions"
                            @start="imagedrag = true"
                            @end="imagedrag = false"
                            >
                                <transition-group type="transition" tag="div" class="row" :name="!imagedrag ? 'flip-list' : null">
                                    <div v-for="(pcolorimg, index) in pcolorstockshape.input.image" :key="`carousel-item-inputs-${index}`" class="p-1 col-xl-4 col-lg-12 mb-4">
                                    
                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(pcolorstockshape.input.image, index, {...pcolorimg, _isSelected: !pcolorimg._isSelected})" 
                                        :checked="pcolorimg._isSelected">
                                    </div>
                                    
                                        <a href="javascript:void(0)" @click.stop="stcclr_choosecolorstockshapeimage(index)" class="d-block link-img img-form-wrap mb-2">
                                            <template v-if="!pcolorstockshape?.input?.image?.[index]?.image">
                                                <img src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                                            </template>
                                            <template v-else>
                                                <template v-if="!pcolorimg?.type">
                                                    <img :src="pcolorstockshape.input.image[index].image" alt="" class="full-width">
                                                </template>
                                                <template v-else>
                                                    <img v-if="pcolorimg.image.split('.').pop() != 'html'" :src="pcolorstockshape.input.image[index].image" alt="" class="full-width">
                                                    <div v-if="pcolorimg.image.split('.').pop() == 'html'" class="anim-thumbnail">
                                                        <iframe :src="pcolorstockshape.input.image[index].image" class="full-height full-width" scrolling="no"></iframe>
                                                        <p class="text-center m-0">Click here to choose media</p>
                                                    </div>
                                                </template>
                                            </template>
                                        </a>

                                        <div class="mb-2">
                                            <input type="text" v-model="pcolorstockshape.input.image[index].title" placeholder="Enter Title" class="full-width" />
                                        </div>

                                        <div class="mb-2">
                                            <input type="number" v-model="pcolorstockshape.input.image[index].top" placeholder="Top Position  (%)" class="full-width" />
                                        </div>

                                        <a href="javascript:void(0)" @click.stop="pcolorstockshape.input.image.splice(index, 1);" class="button mt-2">remove</a>
                                    </div>
                                </transition-group>
                            </draggable>
                        </div>

                        <div v-if="!pcolorstockshape.input.autoassignimg" class="mb-2">
                            <a href="javascript:void(0)" 
                            @click.stop="stcclr_addcolorimage" 
                            class="button mb-2 button-secondary">Add main gallery images</a>
                        </div>

                    </div>

                    <hr class="mt-5">

                    
                    <div class="mb-2">
                        <strong class="d-block">TEMPLATES:</strong>
                        <p>Note: Empty template will automatically be removed after submission. You can drag and drop product templates.</p>
                    </div>

                    <div class="mb-3">
                        <button type="button" @click="pcolorstockshape_toggleSelectKey('templates', true)" class="button mr-2">Select All Templates</button>
                        <button type="button" @click="pcolorstockshape_toggleSelectKey('templates', false)" class="button mr-2">Unselect Templates</button>
                        <button type="button" @click="pcolorstockshape_removeCheckedItems_('templates', 'Are you sure you want to remove selected templates?')" v-if="pcolorstockshape_getIsSelectedTemplates.length" class="button mr-2">Remove {{pcolorstockshape_getIsSelectedTemplates.length}} Templates</button>
                    </div>

                    <draggable 
                    v-model="pcolorstockshape.input.templates" 
                    class="v-draggable"
                    tag="div" 
                    v-bind="vueDragOptions"
                    @start="templatedrag = true"
                    @end="templatedrag = false"
                    >
                        <transition-group type="transition" tag="div" class="mb-2 row col-md-12" :name="!templatedrag ? 'flip-list' : null">
                            <div class="col-xl-4 col-lg-12 col-sm-12 p-3 mb-3 v-drag-item" v-for="(dl, index) in pcolorstockshape.input.templates" :key="`download-${index}`">
                                <div class="pt-5 pb-3" style="border: 1px solid rgb(195, 195, 195);border-radius: 5px;position:relative;">
                                    
                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(pcolorstockshape.input.templates, index, {...dl, _isSelected: !dl._isSelected})" 
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
                                        <a class="button mt-2 mr-2" href="javascript:void(0)" @click.stop="stcclr_selectTemplateLink(index)">select</a>
                                        <a class="button mt-2 button-danger" href="javascript:void(0)" @click.stop="pcolorstockshape.input.templates.splice(index, 1)">remove</a>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </draggable>

                    <div class="mb-2">
                        <a class="button button-secondary" @click.stop="stcclr_addTemplates">Add Template</a>
                    </div>


                    <hr class="mt-5">


                    <div class="mb-2">
                        <strong class="d-block">IDEA GALLERIES:</strong>
                        <p>Note: Empty idea gallery images will automatically be removed after submission, You can drag and drop product idea galleries images.</p>
                        <p v-if="!pcolorstockshape.input.hid">
                            <label>
                                <input type="checkbox" v-model="pcolorstockshape.input.autoassignidea" :true-value="1" :false-value="null"> auto-assign idea gallery images? 
                            </label>
                        </p>
                    </div>

                    <div class="mb-3">
                        <button type="button" @click="pcolorstockshape_toggleSelectKey('idea_galleries', true)" class="button mr-2">Select All Idea Gallery</button>
                        <button type="button" @click="pcolorstockshape_toggleSelectKey('idea_galleries', false)" class="button mr-2">Unselect Idea Gallery</button>
                        <button type="button" @click="pcolorstockshape_removeCheckedItems_('idea_galleries', 'Are you sure you want to remove selected idea gallery?')" v-if="pcolorstockshape_getIsSelectedIdeaGallery.length" class="button mr-2">Remove {{pcolorstockshape_getIsSelectedIdeaGallery.length}} Idea Gallery</button>
                        <button v-if="stcclr_selectedColor && stcclr_selectedStockShape && stcclr_selectedColor?.colorname && stcclr_selectedStockShape?.code" type="button" @click="pullAnimatedMediasColorsStockShape({
                            id: pcolorstockselectedcombo.hid,
                            color: stcclr_selectedColor?.colorname,
                            code: stcclr_selectedStockShape?.code,
                            type: 'ig'
                        })" class="button mr-2">Pull animated medias</button>
                    </div>

                    <div v-if="pcolorstockshape.input.idea_galleries && !pcolorstockshape.input.autoassignidea" class="mb-2 col-md-12">
                        <draggable 
                        v-model="pcolorstockshape.input.idea_galleries" 
                        class="v-draggable"
                        tag="div" 
                        v-bind="vueDragOptions"
                        @start="ideagallerydrag = true"
                        @end="ideagallerydrag = false"
                        >
                            <transition-group type="transition" tag="div" class="row" :name="!ideagallerydrag ? 'flip-list' : null">
                                <div v-for="(pcolorimg, index) in pcolorstockshape.input.idea_galleries" :key="`carousel-item-inputs-${index}`" class="p-1 col-xl-4 col-lg-12 mb-4">
                                    
                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(pcolorstockshape.input.idea_galleries, index, {...pcolorimg, _isSelected: !pcolorimg._isSelected})" 
                                        :checked="pcolorimg._isSelected">
                                    </div>


                                    <a href="javascript:void(0)" @click.stop="stcclr_chooseideagalleryimage(index)" class="d-block link-img img-form-wrap mb-2">
                                        <template v-if="!pcolorstockshape?.input?.idea_galleries?.[index]?.image">
                                            <img src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                                        </template>
                                        <template v-else>
                                            <template v-if="!pcolorimg?.type">
                                                <img :src="pcolorstockshape.input.idea_galleries[index].image" alt="" class="full-width">
                                            </template>
                                            <template v-else>
                                                <img v-if="pcolorimg.image.split('.').pop() != 'html'" :src="pcolorstockshape.input.idea_galleries[index].image" alt="" class="full-width">
                                                <div v-if="pcolorimg.image.split('.').pop() == 'html'" class="anim-thumbnail">
                                                    <iframe :src="pcolorstockshape.input.idea_galleries[index].image" class="full-height full-width" scrolling="no"></iframe>
                                                    <p class="text-center m-0">Click here to choose media</p>
                                                </div>
                                            </template>
                                        </template>
                                    </a>

                                    <div v-if="!pcolorstockshape.input.idea_galleries[index].usecurfile" class="mb-2">
                                        <input type="text" readonly placeholder="Download Link URL" v-model="pcolorstockshape.input.idea_galleries[index].downloadLink" class="full-width mb-2">
                                        <a href="javascript:void(0)" @click.stop="stcclr_ideagallerydownloadfileselect(index)" class="button mb-2">Select File</a>
                                    </div>

                                    <label for="per_thousand" class="mb-2 d-block"> 
                                    <input 
                                    v-model="pcolorstockshape.input.idea_galleries[index].usecurfile" 
                                    type="checkbox" 
                                    value="per_thousand"
                                    :true-value="1"
                                    :false-value="0">
                                    Use current photo for the download link url.</label>

                                    <div class="mb-2">
                                        <input type="text" v-model="pcolorstockshape.input.idea_galleries[index].text" placeholder="Enter Title" class="full-width" />
                                    </div>

                                    <div class="mb-2">
                                        <input type="number" v-model="pcolorstockshape.input.idea_galleries[index].top" placeholder="Top Position  (%)" class="full-width" />
                                    </div>

                                    <a href="javascript:void(0)" @click.stop="pcolorstockshape.input.idea_galleries.splice(index, 1);" class="button mt-2">remove</a>
                                </div>
                            </transition-group>
                        </draggable>
                    </div>

                    <div class="mb-2" v-if="!pcolorstockshape.input.autoassignidea">
                        <a class="button button-secondary" @click.stop="stcclr_addIdeaGallery">Add Idea Gallery Image</a>
                    </div>

                    <hr class="mt-5">


                    <div class="mt-5 mt-3 floating-button-save">
                        <button v-if="pcolorstockshape.input.id==null" type="submit" class="button button-primary">Add Color + Stockshape</button>
                        <button v-else type="submit" class="button button-primary">Save Color + Stockshape</button>
                        <a href="javascript: void(0)" class="button" @click.stop="resetColorsStockshapeInputs({product_print_method_id:pcolorstockshape.input.product_print_method_id, index: null}); pcolorstockshapestate='lists'">View Color + Stockshapes</a>
                    </div>

                </form>
            </div>

        </div>


        <!-- lists here. -->


        <div v-else>
            
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <a href="javascript:void(0)" @click.stop="resetColorsStockshapeInputs({product_print_method_id:pcolorstockshape.input.product_print_method_id, index: null}); pcolorstockshapestate='form'" class="button">Add Color + Stockshape</a>
                </div>
                <div class="col-md-6">
                    <a href="javascript:void(0)" @click.stop="stcclr_generateColorStockshape" class="button">Generate Color + Stockshape</a>
                    <p>Generate combination of existing colors + stockshape from this product.</p>
                </div>
            </div>

            <div class="mb-3">
                <button type="button" @click="selectAll_color_stockshape_" class="button mr-2">Select All</button>
                <button type="button" @click="unselectAll_color_stockshape_" class="button mr-2">Unselect</button>
                <button type="button" @click="removeCheckedItems_color_stockshape_" v-if="getIsSelectedColorStockshape.length" class="button mr-2">Remove ({{getIsSelectedColorStockshape.length}})</button>
            </div>


            <div class="mb-3">
                <form @submit.prevent="searchEntryColorStockshapeQueryFilter" style="text-align:right;">
                    <input placeholder="Search" type="text" v-model="pcolorstockshape.query" />
                </form>
            </div>

            <div class="row" v-if="pcolorstockshape.loading">
                <div class="col-md-12">
                    <p style="text-align: center;">Loading..</p>
                </div>
            </div>

            <div class="row" v-else>
                <div v-if="!pcolorstockshape.data.length" class="col-md-12">
                    <p style="text-align: center;">There is no product color.</p>
                </div>

                <div v-else class="col-md-12 mt-3 row">

                    <div v-for="(pcs, index) in pcolorstockshape.data" :key="`pcolor-data-${index}`" class="col-md-4">
                        <div class="mb-4 p-2" style="border: 1px solid #e0e0e0;">

                            <div style="float:right;">
                                <input type="checkbox" 
                                @input="() => $set(pcolorstockshape.data, index, {...pcs, _isSelected: !pcs._isSelected})" 
                                :checked="pcs._isSelected">
                            </div>

                            <div class="d-block mb-2" v-if="pcs.thecolor">
                                <span class="color-circle mt-2" v-if="!pcs.thecolor.iscolorimage" :style="`background: ${pcs.thecolor.colorhex}; width: 30px; height: 30px; border-radius: 100%; display: inline-block;`"></span>
                                <span class="color-circle mt-2 d-block" v-else :style="`background: url(${pcs.thecolor.colorimageurl}) center no-repeat; background-size: cover; width: 30px; height: 30px; border-radius: 100%; display: inline-block;`"></span>
                                <strong>{{pcs.thecolor.colorname}} - {{pcs.theshape.code}}</strong>
                            </div>
                            <div class="d-block mb-2">Priority #: {{ pcs.priority }}</div>
                            <div class="d-block mb-2">Images: {{ pcs.imagedata.length }}</div>
                            <div class="d-block mb-2">Templates: {{ pcs.counttemplates }}</div>
                            <div class="d-block mb-2">VDS SAGE PRODUCT ID: {{ pcs.vdsproductid }}</div>
                            <div class="d-block mb-2">VDS SAGE ITEM #: {{ pcs.vdsid }}</div>
                            <div class="d-block mt-3 mb-2">

                                <a href="javascript: void(0)" @click.stop="resetColorsStockshapeInputs({
                                    ...pcs,
                                    product_color_id: pcs.thecolor.hid,
                                    product_stockshape_id: pcs.theshape.hid,
                                    id: pcs.hid,
                                    product_print_method_id:pcolorstockshape.input.product_print_method_id, 
                                    index: index,
                                    image: [...pcs.imagedata],
                                    idea_galleries: [...pcs.ideagallerydata],
                                    templates: [...pcs.templatedata]
                                }); pcolorstockshapestate='form';" class="button">Edit</a>

                                <a href="javascript: void(0)" @click.stop="stcclr_removeproductColorStockShape(pcs.hid, index)" class="button">Remove</a>
                            </div>
                        </div>
                    </div>

                    <p v-if="pcolorstockshape.pagination.metas.next_page_url" style="text-align: center;width:100%;">
                        <button v-if="!pcolorstockshape.loadingNext" @click="getColorStockshapeNextButton" class="button primary">Load More</button>
                        <span v-else>Loading...</span>
                    </p>

                </div>
                
            </div>

        </div>

        

    </div>
</div>

</div>