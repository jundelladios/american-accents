<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="input.index!=null">Edit Subcategory</span>
                <span v-else>New Subcategory</span>
            </h1>
            
            <form action="/" @submit.prevent="saveSubCategory" class="mt-3" autocomplete="off">
                <!-- <div class="mb-3">
                    <label>Image should be 1920x604</label>
                    <a href="javascript:void(0)" @click.stop="wpImage" class="d-block link-img">
                        <img v-if="input.banner_img" :src="input.banner_img" alt="" class="full-width">
                        <img v-else src="<?php // echo american_accent_plugin_base_url() . 'application/assets/img/landscape-placeholder.png'; ?>" alt="" class="full-width">
                    </a>
                    <a href="javascript:void(0)" v-if="input.banner_img" @click.stop="input.banner_img=''"><small>Remove Banner</small></a>
                </div> -->
                <div class="mb-3">
                    <label class="d-block mb-2" for="sub_name">Subcategory Name</label>
                    <input type="text" 
                    v-model="input.sub_name" 
                    id="sub_name" 
                    class="full-width" 
                    v-validate="'required'"
                    data-vv-as="subcategory name"
                    name="sub_name" />
                    <span class="v-error">{{errors.first('sub_name')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2" for="sub_name">Subcategory Alternative</label>
                    <textarea type="text" 
                    v-model="input.sub_name_alt" 
                    class="full-width"></textarea>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="sub_slug">Subcategory Slug</label>
                    <input type="text" 
                    v-model="input.sub_slug" 
                    id="sub_slug" 
                    class="d-block" 
                    v-validate="'required'"
                    data-vv-as="subcategory slug"
                    name="sub_slug" />
                    <small class="d-block">Notes: Special characters and spacing will be automatically converted to "-"</small>
                    <span class="v-error">{{errors.first('sub_slug')}}</span>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="priority">Priority #</label>
                    <input type="number" 
                    v-model="input.priority" 
                    id="priority" 
                    class="d-block" 
                    v-validate="'required'"
                    data-vv-as="priority"
                    name="priority" />
                    <span class="v-error">{{errors.first('priority')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2">Categorize AS:</label>
                    <div v-if="categorizeLists.loading">
                        <p>Loading...</p>
                    </div>
                    <div v-else>
                        <div v-if="categorizeLists.data">
                            <label v-for="(catas, index) in categorizeLists.data" :key="`categorize-as-${index}`" class="d-block mb-3">
                                <input type="radio" :value="catas.categorize_as" v-model="input.categorize_as">
                                {{catas.categorize_as}}
                            </label>
                        </div>
                        <div class="mt-3">
                            <input type="text" v-model="input.categorize_as">
                            <small class="d-block">If not in the list, enter here.</small>
                        </div>
                    </div>
                </div>

                <!-- <div class="mb-3">
                    <label class="d-block mb-2" for="desc">Description (alias)</label>
                    <input type="text" v-model="input.sub_description" />
                </div> -->

                <div class="mb-3">
                    <h4 class="mb-0">Catalogs</h4>
                    <p>You can drag and drop to arrange catalog.</p>
                </div>
                
                <draggable 
                v-model="input.catalogs" 
                class="v-draggable spec"
                tag="div" 
                v-bind="vueDragOptions"
                @start="catalogdrag = true"
                @end="catalogdrag = false"
                key="catalogdrag-drag"
                >
                    <transition-group type="transition" tag="div" :name="!catalogdrag ? 'flip-list' : null">
                        <div v-for="(cat, cati) in input.catalogs" :key="`input-catalog-${cati}`" class="mb-3 p-3 v-drag-item" style="border:1px solid #ccc;">

                            <a href="javascript:void(0)" @click.stop="catalogimageselect(cati)" class="d-block link-img mb-3" style="width: fit-content;">
                                <img v-if="cat.image" :src="cat.image" alt="" style="max-width: 100px;">
                                <img v-else src="<?php echo american_accent_plugin_base_url() . 'application/assets/img/placeholder-square.png'; ?>" alt="" style="max-width: 100px;">
                            </a>

                            <input type="text" v-model="input.catalogs[cati].catalog" class="full-width mb-2" disabled placeholder="Catalog">
                            <input type="text" v-model="input.catalogs[cati].title" placeholder="Enter Catalog Title" class="full-width mb-2">
                            <a href="#" class="mb-2 mr-2" @click.stop="selectCatalog(cati)">Select Catalog</a>
                            <a href="#" class="mb-2" @click.stop="input.catalogs.splice(cati, 1)">Remove</a>
                        </div>
                    </transition-group>
                </draggable>

                <div class="mb-3">
                    <button type="button" class="button" @click="input.catalogs.push({ catalog: '', title: '', image: '' });">Add Catalog</button>
                </div>

                <h4 class="mb-3">SEO Contents</h4>
                
                <div class="mb-3">
                    <textarea rows="3" v-model="input.seo_content.description" placeholder="Meta Description" class="full-width"></textarea>
                </div>
                <div class="mb-3">
                    <label>Seo Banner Image (600x314)</label>
                    <a href="javascript:void(0)" @click.stop="seoImage" class="d-block link-img">
                        <img v-if="input.seo_content.image" :src="input.seo_content.image" alt="" class="full-width">
                        <img v-else src="<?php echo american_accent_plugin_base_url() . 'application/assets/img/landscape-placeholder.png'; ?>" alt="" class="full-width">
                    </a>
                    <a v-if="input.seo_content.image" href="javascript:void(0)" @click="input.seo_content.image=null">remove</a>
                </div>
                <div class="mb-3">
                    <p>More information with <a href="https://developers.facebook.com/docs/sharing/webmasters/" target="_blank">Open Graph</a>.</p>
                </div>

                <div class="mb-3">
                    <button id="btn" typpe="submit" v-if="input.index!=null" class="button button-primary">Save Changes</button>
                    <button id="btn" typpe="submit" v-else class="button button-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>