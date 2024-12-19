<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="input.index!=null">Edit Category</span>
                <span v-else>New Category</span>
            </h1>
            <form action="/" @submit.prevent="saveCategory" class="mt-3" autocomplete="off">
                
                <!--
                <div class="mb-3">
                    <label>Image should be 1920x604</label>
                    <a href="javascript:void(0)" @click.stop="wpImage" class="d-block link-img">
                        <img v-if="input.category_banner" :src="input.category_banner" alt="" class="full-width">
                        <img v-else src="<?php echo american_accent_plugin_base_url() . 'application/assets/img/landscape-placeholder.png'; ?>" alt="" class="full-width">
                    </a>
                    <a href="javascript:void(0)" v-if="input.category_banner" @click.stop="input.category_banner=''"><small>Remove Banner</small></a>
                    <textarea v-model="input.category_banner_content" rows="3" class="full-width mt-2" placeholder="Category Banner HTML Content."></textarea>
                </div>
                    -->


                <div class="mb-3">
                    <h4 class="mb-0">Banners</h4>
                    <p>You can drag and drop to arrange the banners.</p>
                </div>
                
                <draggable 
                v-model="input.bannerlist" 
                class="v-draggable spec"
                tag="div" 
                v-bind="vueDragOptions"
                @start="dragGrid = true"
                @end="dragGrid = false"
                key="bannerlist-drag"
                >
                    <transition-group type="transition" tag="div" :name="!dragGrid ? 'flip-list' : null">
                        <div v-for="(cat, cati) in input.bannerlist" :key="`input-catalog-${cati}`" class="mb-3 p-3 v-drag-item" style="border:1px solid #ccc;">

                            <a href="javascript:void(0)" @click.stop="selectBanner(cati)" class="d-block link-img mb-3" style="width: fit-content;">
                                <img v-if="cat.image" :src="cat.image" alt="" style="max-width: 100px;">
                                <img v-else src="<?php echo american_accent_plugin_base_url() . 'application/assets/img/placeholder-square.png'; ?>" alt="" style="max-width: 100px;">
                            </a>

                            <input type="text" v-model="input.bannerlist[cati].title" placeholder="Enter Banner Title" class="full-width mb-2">
                            <input type="text" v-model="input.bannerlist[cati].alt" class="full-width mb-2" placeholder="Enter Banner Alt Text">
                            <a href="#" class="mb-2 mr-2" @click.stop="selectBanner(cati)">Select Banner</a>
                            <a href="#" class="mb-2" @click.stop="input.bannerlist.splice(cati, 1)">Remove</a>
                        </div>
                    </transition-group>
                </draggable>

                <div class="mb-3">
                    <button type="button" class="button" @click="() => {
                        if(!Array.isArray(input.bannerlist)) {
                            input.bannerlist = [];
                        }
                        input.bannerlist.push({ alt: '', title: '', image: '' });
                    }">Add Banner</button>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2" for="catname">Category Name</label>
                    <input type="text" 
                    v-model="input.cat_name" 
                    id="catname" 
                    class="full-width"
                    v-validate="'required'"
                    data-vv-as="category name"
                    name="cat_name" />
                    <span class="v-error">{{errors.first('cat_name')}}</span>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="catslug">Category Slug (unique)</label>
                    <input type="text" 
                    v-model="input.cat_slug" 
                    id="catslug" 
                    class="d-block"
                    v-validate="'required'"
                    data-vv-as="category slug"
                    name="cat_slug" />
                    <small class="d-block">Notes: Special characters and spacing will be automatically converted to "-"</small>
                    <span class="v-error">{{errors.first('cat_slug')}}</span>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="priority">Priority #</label>
                    <input type="number" v-model="input.priority" id="priority" class="d-block"
                    v-validate="'required'"
                    data-vv-as="priority"
                    name="priority" />
                    <span class="v-error">{{errors.first('priority')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2" for="priority">
                        <input type="checkbox" v-model="input.template_section" :true-value="1" :false-value="0" />
                        Include to Template Section?</label>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2" for="desc">Description</label>
                    <editor v-model="input.notes" />
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

                <div class="floating-button-save">
                    <button typpe="submit" id="btn" v-if="input.index!=null" class="button button-primary">Save Changes</button>
                    <button typpe="submit" id="btn" v-else class="button button-primary">Save Category</button>

                    <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="button button-default">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>