<div class="container">
    <div class="d-flex">
        <?php for($i=0;$i<4;$i++): ?>
            <div class="mt-5 mr-2 animation skeleton" style="height: 15px;max-width:80px;"></div>
        <?php endfor; ?>
    </div>
    <hr>
    <div class="d-flex">
        <div class="animation skeleton" style="height: 50px;max-width:450px;"></div>
    </div>

    <div class="d-flex product-wrap mt-5 justify-content-between">
        <div class="prod-col prod-col-left">
            <div class="animation skeleton" style="height:390px;"></div>

            <div class="mt-2">
                <?php for($i=0;$i<4;$i++): ?>
                    <div class="mt-3 animation skeleton" style="height: 52px;max-width:300px;"></div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="prod-col prod-col-right">
            <div class="mb-5 d-flex justify-content-between">
                <div style="width:100%;">
                    <div class="animation mb-2 skeleton d-block" style="max-width: 200px; height: 30px;"></div>
                    <div class="animation skeleton d-block" style="max-width: 230px; height: 20px;"></div>
                </div>
                <div style="width:100%;max-width:150px;">
                    <div class="animation mb-2 skeleton d-block" style="max-width: 200px; height: 30px;"></div>
                    <div class="animation mb-2 skeleton d-block" style="max-width: 230px; height: 20px;"></div>
                    <div class="animation skeleton d-block" style="max-width: 230px; height: 10px;"></div>
                </div>
            </div>

            <div class="mb-5">
                <div class="animation mb-3 skeleton d-block" style="max-width: 230px; height: 30px;"></div>
                <?php for($i=0;$i<4;$i++): ?>
                    <div class="mb-2 animation skeleton" style="height: 10px;max-width:calc(100% - <?php echo $i*50; ?>px);"></div>
                <?php endfor; ?>
            </div>

        </div>
    </div>
</div>