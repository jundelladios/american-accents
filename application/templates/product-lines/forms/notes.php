<div class="mt-3 mb-3">
    <label class="d-block mb-3"><strong>Pricing Notes.</strong></label>
    <a class="button mb-3" @click.stop="addNote">Add Note</a>
    <div v-for="(note, index) in inputs.pnotes" :key="`pline-notes-${index}`" class="mb-3 d-flex">
        <textarea placeholder="Enter note." v-model="inputs.pnotes[index]" class="full-width" rows="3"></textarea>
        <div class="ml-3">
            <a href="javascript:void(0)" v-if="index!=0" @click.stop="removeNote(index)" class="button">remove</a>
        </div>
        <hr>
    </div>

    <div class="mb-3">
        <label><strong>Specification Notes.</strong></label>
        <editor v-model="inputs.pnotes2" />
    </div>
</div>