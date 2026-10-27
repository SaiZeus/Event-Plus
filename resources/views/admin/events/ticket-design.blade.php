
@extends('layouts.admin')

@section('title', 'Ticket Design Studio')

@section('content')
<div class="container-fluid py-4 ticket-studio">
    <div class="studio-header">
        <div>
            <a href="{{ route('admin.events.index') }}" class="studio-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Events
            </a>
            <div class="studio-title-row">
                <div class="studio-title-icon">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <h1>Ticket Design Studio</h1>
                    <p>Design beautiful tickets for {{ $event->title }}</p>
                </div>
            </div>
        </div>
        <span class="studio-badge"><span></span> Visual editor</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success studio-alert">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger studio-alert">
            <div>
                <strong>Please check the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(!isset($categories) || $categories->isEmpty())
        <div class="studio-empty-state">
            <i class="fa-solid fa-ticket"></i>
            <h3>No ticket categories yet</h3>
            <p>Create a ticket category before designing its ticket.</p>
            <a href="{{ route('admin.events.index') }}" class="studio-btn studio-btn-primary">
                Back to Events
            </a>
        </div>
    @else
        <form id="ticketDesignForm"
              method="POST"
              enctype="multipart/form-data"
              action="{{ route('admin.events.ticket-design.save', $event) }}">
            @csrf

            <input type="hidden" name="ticket_design" id="ticket_design">
            <input type="hidden" name="remove_background" id="remove_background" value="0">

            <section class="studio-config">
                <div class="config-event">
                    <div class="config-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <div>
                        <span class="eyebrow">CURRENT EVENT</span>
                        <strong>{{ $event->title }}</strong>
                        <small>Ticket appearance settings</small>
                    </div>
                </div>

                <div class="config-category">
                    <label for="ticket_category_id">Ticket category</label>
                    <select id="ticket_category_id" name="ticket_category_id" class="form-select">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected((string) old('ticket_category_id', $selectedCategory?->id) === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <small>Each category has its own background and layout.</small>
                </div>

                <div class="config-actions">
                    <button type="button" class="studio-btn studio-btn-soft" id="loadDesignBtn">
                        <i class="fa-solid fa-rotate"></i> Load design
                    </button>
                    <button type="button" class="studio-btn studio-btn-ghost" id="resetDesignBtn">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Reset layout
                    </button>
                    <button type="submit" class="studio-btn studio-btn-primary" id="saveDesignBtn">
                        <i class="fa-solid fa-floppy-disk"></i> Save design
                    </button>
                </div>
            </section>

            <div class="studio-workspace">
                <aside class="studio-sidebar">
                    <div class="panel-heading">
                        <div>
                            <span class="panel-kicker">CUSTOMIZE</span>
                            <h2>Ticket background</h2>
                        </div>
                        <span class="panel-icon"><i class="fa-solid fa-image"></i></span>
                    </div>

                    <p class="panel-description">
                        Upload your own ticket artwork. The image will appear behind your ticket elements.
                    </p>

                    <div class="upload-box" id="backgroundDropZone">
                        <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <strong>Upload ticket artwork</strong>
                        <span>JPG, JPEG or PNG</span>
                        <span class="upload-recommended">Recommended canvas: 1600 × 517 px</span>
                        <label for="background_image" class="studio-btn studio-btn-soft upload-button">
                            <i class="fa-solid fa-folder-open"></i> Choose image
                        </label>
                        <input type="file"
                               id="background_image"
                               name="background_image"
                               accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                               hidden>
                        <small id="uploadFileName" class="upload-filename">No new image selected</small>
                    </div>

                    <div class="background-actions">
                        <button type="button" class="studio-btn studio-btn-ghost" id="removeBackgroundBtn">
                            <i class="fa-solid fa-trash-can"></i> Remove background
                        </button>
                        <button type="button" class="studio-btn studio-btn-ghost" id="restoreBackgroundBtn">
                            <i class="fa-solid fa-rotate-left"></i> Restore saved
                        </button>
                    </div>

                    <div class="background-status" id="backgroundStatus">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>No background loaded.</span>
                    </div>

                    <div class="sidebar-divider"></div>

                    <div class="panel-heading">
                        <div>
                            <span class="panel-kicker">BUILD YOUR TICKET</span>
                            <h2>Elements</h2>
                        </div>
                        <span class="panel-icon"><i class="fa-solid fa-layer-group"></i></span>
                    </div>

                    <p class="panel-description">
                        Add information and drag it into position on the canvas.
                    </p>

                    <div class="element-list">
                        <button type="button" class="element-option add-element" data-type="qr">
                            <span class="element-icon qr-icon"><i class="fa-solid fa-qrcode"></i></span>
                            <span><strong>QR Code</strong><small>Ticket verification</small></span>
                            <i class="fa-solid fa-plus element-plus"></i>
                        </button>
                        <button type="button" class="element-option add-element" data-type="attendee_name">
                            <span class="element-icon name-icon"><i class="fa-solid fa-user"></i></span>
                            <span><strong>Attendee name</strong><small>Guest information</small></span>
                            <i class="fa-solid fa-plus element-plus"></i>
                        </button>
                        <button type="button" class="element-option add-element" data-type="phone">
                            <span class="element-icon phone-icon"><i class="fa-solid fa-phone"></i></span>
                            <span><strong>Phone number</strong><small>Contact information</small></span>
                            <i class="fa-solid fa-plus element-plus"></i>
                        </button>
                        <button type="button" class="element-option add-element" data-type="ticket_number">
                            <span class="element-icon ticket-icon"><i class="fa-solid fa-ticket"></i></span>
                            <span><strong>Ticket number</strong><small>Unique reference</small></span>
                            <i class="fa-solid fa-plus element-plus"></i>
                        </button>
                        <button type="button" class="element-option add-element" data-type="bib_number">
                            <span class="element-icon bib-icon"><i class="fa-solid fa-hashtag"></i></span>
                            <span><strong>Bib number</strong><small>Runner identification</small></span>
                            <i class="fa-solid fa-plus element-plus"></i>
                        </button>
                        <button type="button" class="element-option add-element" data-type="event_name">
                            <span class="element-icon event-icon"><i class="fa-solid fa-flag-checkered"></i></span>
                            <span><strong>Event name</strong><small>Event branding</small></span>
                            <i class="fa-solid fa-plus element-plus"></i>
                        </button>
                    </div>

                    <div class="tip-box">
                        <i class="fa-solid fa-lightbulb"></i>
                        <div>
                            <strong>Design tip</strong>
                            <p>Keep the QR code clear and leave enough space around it for scanning.</p>
                        </div>
                    </div>

                    <div class="sidebar-divider"></div>

                    <div class="panel-heading">
                        <div>
                            <span class="panel-kicker">FINE-TUNE</span>
                            <h2>Element properties</h2>
                        </div>
                        <span class="panel-icon"><i class="fa-solid fa-sliders"></i></span>
                    </div>

                    <div id="propertiesPanel" class="properties-panel">
                        <div class="properties-empty">
                            <i class="fa-solid fa-arrow-pointer"></i>
                            <p>Select an element on the canvas to customize its position, size and style.</p>
                        </div>
                    </div>
                </aside>

                <section class="studio-editor">
                    <div class="editor-toolbar">
                        <div>
                            <strong><span class="live-dot"></span> Canvas workspace</strong>
                            <small>Drag elements to position them</small>
                        </div>
                        <span class="canvas-size"><i class="fa-solid fa-expand"></i> 1600 × 517</span>
                    </div>

                    <div class="canvas-stage">
                        <div class="stage-label">
                            <span><i class="fa-solid fa-grip"></i> DESIGN CANVAS</span>
                            <span>1600 × 517 units</span>
                        </div>

                        <div class="ticket-canvas-viewport">
                            <div id="ticketCanvas" aria-label="Ticket design canvas">
                                <div class="canvas-empty" id="canvasEmpty">
                                    <div>
                                        <span class="canvas-empty-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                                        <strong>Your canvas is ready</strong>
                                        <span>Upload a background or add an element to get started.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="canvas-footer">
                            <span><i class="fa-solid fa-computer-mouse"></i> Select and drag elements</span>
                            <button type="button" id="deleteElementBtn" class="delete-selected-btn" disabled>
                                <i class="fa-solid fa-trash-can"></i> Delete selected
                            </button>
                        </div>
                    </div>

                    <div class="editor-note">
                        <span><i class="fa-solid fa-circle-info"></i></span>
                        <div>
                            <strong>Live design preview</strong>
                            <p>Upload a background, select an element, and adjust its position, dimensions, font size, color, and rotation. Save your design to apply the layout to downloaded tickets.</p>
                        </div>
                    </div>
                </section>
            </div>
        </form>
    @endif
</div>

<style>
.ticket-studio {
    --purple: #6338c8;
    --purple-dark: #48239c;
    --purple-soft: #f2edff;
    --ink: #281e3d;
    --muted: #887f98;
    --border: #e9e4f1;
    color: var(--ink);
    padding-bottom: 35px !important;
}
.ticket-studio * { box-sizing: border-box; }
.ticket-studio .studio-header {
    display:flex; justify-content:space-between; align-items:flex-end;
    gap:20px; margin-bottom:25px;
}
.ticket-studio .studio-back {
    display:inline-flex; align-items:center; gap:9px; margin-bottom:18px;
    color:#7d748e; text-decoration:none; font-size:12px; font-weight:700;
}
.ticket-studio .studio-back:hover { color:var(--purple); }
.ticket-studio .studio-title-row { display:flex; align-items:center; gap:14px; }
.ticket-studio .studio-title-icon {
    width:52px; height:52px; display:grid; place-items:center;
    color:#fff; font-size:21px; border-radius:16px;
    background:linear-gradient(140deg,#865eec,#5830b4);
    box-shadow:0 8px 20px #6338c82b;
}
.ticket-studio .studio-title-row h1 {
    font-size:clamp(22px,2.3vw,30px); font-weight:850;
    letter-spacing:-.8px; margin:0 0 5px;
}
.ticket-studio .studio-title-row p { margin:0; color:var(--muted); font-size:12px; }
.ticket-studio .studio-badge {
    display:flex; align-items:center; gap:9px; padding:10px 14px;
    background:#faf8ff; border:1px solid #e8e0fa; color:#6540b7;
    border-radius:30px; font-size:11px; font-weight:800; white-space:nowrap;
}
.ticket-studio .studio-badge span,.ticket-studio .live-dot {
    display:inline-block; width:7px; height:7px; border-radius:50%;
    background:#25b985; box-shadow:0 0 0 4px #25b98519;
}
.ticket-studio .studio-alert {
    border-radius:12px; font-size:13px; margin-bottom:20px;
}
.ticket-studio .studio-empty-state {
    padding:60px 20px; text-align:center; background:#fff;
    border:1px solid var(--border); border-radius:18px;
}
.ticket-studio .studio-empty-state > i {
    font-size:30px; color:var(--purple); background:var(--purple-soft);
    padding:20px; border-radius:18px; margin-bottom:18px;
}
.ticket-studio .studio-empty-state h3 { font-size:21px; font-weight:800; }
.ticket-studio .studio-empty-state p { color:var(--muted); font-size:13px; }
.ticket-studio .studio-config {
    display:flex; align-items:center; flex-wrap:wrap; gap:20px;
    padding:19px 21px; margin-bottom:23px; background:#fff;
    border:1px solid var(--border); border-radius:16px;
    box-shadow:0 5px 20px #2d1c5308;
}
.ticket-studio .config-event {
    display:flex; align-items:center; gap:12px; flex:1 1 180px; min-width:0;
}
.ticket-studio .config-icon {
    width:43px; height:43px; display:grid; place-items:center;
    flex-shrink:0; background:var(--purple-soft); color:var(--purple);
    border-radius:12px; font-size:17px;
}
.ticket-studio .config-event .eyebrow {
    display:block; color:#9a90ad; font-size:9px; letter-spacing:1px;
    font-weight:850; margin-bottom:4px;
}
.ticket-studio .config-event strong {
    display:block; overflow-wrap:anywhere; font-size:13px; font-weight:850;
}
.ticket-studio .config-event small { display:block; margin-top:4px; color:#958ba5; font-size:10px; }
.ticket-studio .config-category { flex:1 1 190px; min-width:160px; }
.ticket-studio .config-category label {
    display:block; margin-bottom:7px; color:#5b526d; font-size:11px; font-weight:800;
}
.ticket-studio .config-category .form-select {
    min-height:41px; border:1px solid #e6dfef; border-radius:9px;
    background-color:#fcfbff; font-size:12px; font-weight:600;
    box-shadow:none; color:#392c52;
}
.ticket-studio .config-category small { display:block; margin-top:6px; color:#9a91a8; font-size:10px; }
.ticket-studio .config-actions { display:flex; flex-wrap:wrap; gap:8px; }
.ticket-studio .studio-btn {
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    min-height:39px; padding:9px 12px; border-radius:9px;
    border:1px solid transparent; font-size:11px; font-weight:750;
    text-decoration:none; cursor:pointer;
    transition:background .2s,border-color .2s,transform .2s;
}
.ticket-studio .studio-btn:hover { transform:translateY(-1px); }
.ticket-studio .studio-btn-primary {
    color:#fff; background:linear-gradient(135deg,#764be0,#5931b7);
    border-color:#6338c8; box-shadow:0 5px 12px #6338c82b;
}
.ticket-studio .studio-btn-primary:hover { color:#fff; background:var(--purple-dark); }
.ticket-studio .studio-btn-soft { color:#6540b5; background:#f4efff; border-color:#e7dcff; }
.ticket-studio .studio-btn-soft:hover { background:#ebe2ff; }
.ticket-studio .studio-btn-ghost { color:#777086; background:#fff; border-color:#e8e3ef; }
.ticket-studio .studio-btn-ghost:hover { background:#f9f7fc; }
.ticket-studio .studio-workspace {
    display:grid; grid-template-columns:minmax(270px,310px) minmax(0,1fr);
    gap:22px; align-items:start;
}
.ticket-studio .studio-sidebar,.ticket-studio .studio-editor {
    min-width:0; background:#fff; border:1px solid var(--border);
    border-radius:16px; box-shadow:0 5px 22px #2d1c5308;
}
.ticket-studio .studio-sidebar { padding:20px 17px; }
.ticket-studio .panel-heading { display:flex; align-items:center; justify-content:space-between; gap:10px; }
.ticket-studio .panel-kicker {
    display:block; color:#a096b3; font-size:9px; font-weight:850;
    letter-spacing:1px; margin-bottom:5px;
}
.ticket-studio .panel-heading h2 { margin:0; font-size:16px; font-weight:850; color:#2c2142; }
.ticket-studio .panel-icon {
    width:34px; height:34px; display:grid; place-items:center;
    border-radius:10px; background:#f4efff; color:#7049c7; font-size:13px;
}
.ticket-studio .panel-description { margin:12px 0 16px; color:#8c839c; font-size:11px; line-height:1.7; }
.ticket-studio .upload-box {
    display:flex; flex-direction:column; align-items:center; text-align:center;
    padding:20px 12px 15px; background:#fbf9ff;
    border:1px dashed #c9b8ed; border-radius:13px;
    transition:background .2s,border-color .2s;
}
.ticket-studio .upload-box.drag-over { background:#f0e8ff; border-color:var(--purple); }
.ticket-studio .upload-icon {
    width:43px; height:43px; display:grid; place-items:center;
    border-radius:13px; color:#7549ca; background:#eee6ff;
    font-size:18px; margin-bottom:11px;
}
.ticket-studio .upload-box > strong { color:#413254; font-size:12px; font-weight:850; }
.ticket-studio .upload-box > span { margin-top:5px; color:#92879f; font-size:10px; }
.ticket-studio .upload-box .upload-recommended { color:#a59bb3; font-size:9px; }
.ticket-studio .upload-button { margin-top:13px; }
.ticket-studio .upload-filename {
    display:block; max-width:100%; margin-top:10px; color:#84799a;
    font-size:10px; overflow-wrap:anywhere;
}
.ticket-studio .background-actions {
    display:grid; grid-template-columns:1fr 1fr; gap:7px; margin-top:9px;
}
.ticket-studio .background-actions .studio-btn { padding:8px 6px; font-size:9px; }
.ticket-studio .background-status {
    display:flex; align-items:flex-start; gap:8px; margin-top:10px;
    padding:10px; border-radius:9px; background:#f8f7fb;
    color:#8d829e; font-size:10px; line-height:1.5; overflow-wrap:anywhere;
}
.ticket-studio .background-status i { margin-top:2px; color:#8661cc; }
.ticket-studio .sidebar-divider { height:1px; background:#eee9f4; margin:22px 0; }
.ticket-studio .element-list { display:grid; gap:8px; }
.ticket-studio .element-option {
    width:100%; min-width:0; display:flex; align-items:center; gap:10px;
    text-align:left; padding:10px; border:1px solid #eee9f4;
    border-radius:11px; background:#fff; cursor:pointer;
    transition:background .2s,border-color .2s,transform .2s;
}
.ticket-studio .element-option:hover { background:#fbf9ff; border-color:#cfc0f3; transform:translateX(2px); }
.ticket-studio .element-icon {
    width:37px; height:37px; flex-shrink:0; display:grid; place-items:center;
    border-radius:10px; font-size:14px;
}
.ticket-studio .qr-icon { background:#eee7ff;color:#7549d0; }
.ticket-studio .name-icon { background:#e7f1ff;color:#3977c9; }
.ticket-studio .phone-icon { background:#e4f8f0;color:#21966b; }
.ticket-studio .ticket-icon { background:#fff0e4;color:#d87b36; }
.ticket-studio .bib-icon { background:#ffe9ef;color:#cf5577; }
.ticket-studio .event-icon { background:#e8f4ff;color:#3983b8; }
.ticket-studio .element-option > span:nth-child(2) { min-width:0; flex:1; }
.ticket-studio .element-option strong { display:block; color:#3c3150; font-size:11px; font-weight:800; }
.ticket-studio .element-option small { display:block; margin-top:4px; color:#9b92a8; font-size:9px; }
.ticket-studio .element-plus { color:#a89bbf; font-size:10px; }
.ticket-studio .tip-box {
    display:flex; align-items:flex-start; gap:10px; padding:12px;
    margin-top:17px; background:#f7f3ff; border:1px solid #ece4ff; border-radius:11px;
}
.ticket-studio .tip-box > i {
    width:28px; height:28px; display:grid; place-items:center; flex-shrink:0;
    color:#7148c6; background:#e8ddff; border-radius:8px; font-size:12px;
}
.ticket-studio .tip-box strong { display:block; color:#51348b; font-size:10px; margin:2px 0 5px; }
.ticket-studio .tip-box p { margin:0; color:#817494; font-size:10px; line-height:1.65; }
.ticket-studio .properties-panel .form-label { display:block; color:#655b76; font-size:10px; font-weight:800; margin:0 0 6px; }
.ticket-studio .properties-panel .form-control {
    width:100%; min-height:36px; padding:7px 9px; margin-bottom:12px;
    border:1px solid #e8e2f0; border-radius:8px; background:#fcfbfe;
    color:#372b4b; font-size:11px; box-shadow:none;
}
.ticket-studio .properties-panel .form-control:focus { border-color:#a88be8; box-shadow:0 0 0 3px #6338c814; }
.ticket-studio .properties-panel input[type="color"] { padding:3px; cursor:pointer; }
.ticket-studio .properties-panel input[type="range"] { margin:0 0 12px; }
.ticket-studio .properties-panel .fw-bold {
    padding:10px; border-radius:8px; margin-bottom:13px !important;
    background:#f5f0ff; color:#5736a0; font-size:11px;
}
.ticket-studio .properties-empty {
    padding:19px 12px; text-align:center; border:1px dashed #e3dceb;
    border-radius:11px; background:#faf9fc;
}
.ticket-studio .properties-empty > i {
    display:inline-grid; place-items:center; width:37px; height:37px;
    border-radius:11px; background:#eee7ff; color:#7752c5; margin-bottom:10px;
}
.ticket-studio .properties-empty p { margin:0; color:#9288a1; font-size:10px; line-height:1.7; }
.ticket-studio .editor-toolbar {
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    min-height:73px; padding:15px 20px; border-bottom:1px solid #eee9f4;
}
.ticket-studio .editor-toolbar strong { display:block; color:#302442; font-size:13px; font-weight:850; }
.ticket-studio .editor-toolbar strong .live-dot { margin-right:8px; }
.ticket-studio .editor-toolbar small { display:block; margin-top:5px; color:#92889f; font-size:10px; }
.ticket-studio .canvas-size {
    display:inline-flex; align-items:center; gap:7px; padding:8px 10px;
    border:1px solid #eee8f5; background:#faf9fc; border-radius:8px;
    color:#7d718f; font-size:10px; font-weight:750; white-space:nowrap;
}
.ticket-studio .canvas-size i { color:#8059ce; }
.ticket-studio .canvas-stage { padding:18px; background:#f7f5fa; }
.ticket-studio .stage-label {
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    margin-bottom:12px; color:#a098af; font-size:9px; font-weight:850; letter-spacing:.5px;
}
.ticket-studio .stage-label span:first-child { display:flex; align-items:center; gap:7px; }
.ticket-studio .stage-label i { color:#8664cc; }
.ticket-studio .ticket-canvas-viewport {
    width:100%; overflow:auto; padding:10px; border-radius:12px;
    background-color:#eeebf3;
    background-image:radial-gradient(#d7d0e3 1px,transparent 1px);
    background-size:15px 15px;
}
.ticket-studio #ticketCanvas {
    width:1600px; height:517px; position:relative; overflow:hidden;
    transform-origin:top left; background-color:#fff;
    background-image:
        linear-gradient(#6641b80b 1px,transparent 1px),
        linear-gradient(90deg,#6641b80b 1px,transparent 1px);
    background-size:20px 20px;
    background-position:0 0;
    box-shadow:0 7px 25px #25164424;
    border-radius:3px;
}
.ticket-studio .canvas-empty {
    position:absolute; inset:0; display:flex; align-items:center;
    justify-content:center; text-align:center; pointer-events:none;
}
.ticket-studio .canvas-empty > div { display:flex; flex-direction:column; align-items:center; gap:10px; padding:20px; }
.ticket-studio .canvas-empty-icon {
    width:51px; height:51px; display:grid; place-items:center;
    border-radius:15px; background:#f2edff; color:#835bd0; font-size:21px;
}
.ticket-studio .canvas-empty strong { color:#5d4c78; font-size:15px; font-weight:850; }
.ticket-studio .canvas-empty > div > span:last-child { color:#a49ab3; font-size:11px; }
.ticket-studio .ticket-element {
    position:absolute; display:flex; align-items:center; justify-content:center;
    box-sizing:border-box; padding:4px; text-align:center; white-space:nowrap;
    overflow:hidden; border:1px dashed #8b75c9; background:rgba(255,255,255,.88);
    cursor:grab; user-select:none; touch-action:none;
    transform-origin:center center;
}
.ticket-studio .ticket-element:active { cursor:grabbing; }
.ticket-studio .ticket-element:hover { border-color:#7650ca; }
.ticket-studio .ticket-element.selected {
    border:2px solid #7045cb; background:rgba(246,241,255,.94);
    box-shadow:0 0 0 4px #7045cb22; z-index:10;
}
.ticket-studio .ticket-element .element-label {
    display:block; width:100%; overflow:hidden; text-overflow:ellipsis;
    pointer-events:none; font-weight:750;
}
.ticket-studio .ticket-element.qr { background:#fff; }
.ticket-studio .ticket-element.qr .element-label { white-space:normal; line-height:1.25; }
.ticket-studio .canvas-footer {
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;
    gap:12px; padding-top:15px; color:#948aa2; font-size:10px;
}
.ticket-studio .canvas-footer > span { display:flex; align-items:center; gap:7px; }
.ticket-studio .canvas-footer > span i { color:#8b68d1; }
.ticket-studio .delete-selected-btn {
    display:inline-flex; align-items:center; gap:7px; padding:8px 11px;
    border:1px solid #f0dce1; border-radius:8px; color:#b84b61;
    background:#fff; font-size:10px; font-weight:750; cursor:pointer;
}
.ticket-studio .delete-selected-btn:disabled { opacity:.45; cursor:not-allowed; }
.ticket-studio .editor-note {
    display:flex; align-items:flex-start; gap:11px; margin:16px 18px;
    padding:13px; border:1px solid #eae3fb; border-radius:11px; background:#faf8ff;
}
.ticket-studio .editor-note > span {
    width:29px; height:29px; flex-shrink:0; display:grid; place-items:center;
    border-radius:8px; color:#7149c5; background:#eee7ff; font-size:12px;
}
.ticket-studio .editor-note strong { display:block; color:#5d438f; font-size:10px; margin:2px 0 5px; }
.ticket-studio .editor-note p { color:#9389a2; font-size:10px; line-height:1.7; margin:0; }
@media(max-width:1399px) {
    .ticket-studio .studio-config { gap:15px; }
    .ticket-studio .config-actions { width:100%; justify-content:flex-end; }
}
@media(max-width:991px) {
    .ticket-studio .studio-workspace { grid-template-columns:1fr; }
    .ticket-studio .studio-editor { order:1; }
    .ticket-studio .studio-sidebar { order:2; }
}
@media(max-width:575px) {
    .ticket-studio .studio-header { align-items:flex-start; flex-direction:column; gap:12px; }
    .ticket-studio .studio-title-icon { width:44px; height:44px; border-radius:13px; }
    .ticket-studio .studio-title-row h1 { font-size:22px; }
    .ticket-studio .studio-config { padding:15px; gap:14px; }
    .ticket-studio .config-event,.ticket-studio .config-category { flex-basis:100%; }
    .ticket-studio .config-actions { display:grid; grid-template-columns:1fr 1fr; }
    .ticket-studio .config-actions .studio-btn-primary { grid-column:1/-1; }
    .ticket-studio .studio-sidebar { padding:16px 13px; }
    .ticket-studio .editor-toolbar { padding:14px; }
    .ticket-studio .canvas-stage { padding:10px; }
    .ticket-studio .ticket-canvas-viewport { padding:6px; }
}
</style>

@if(isset($categories) && $categories->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('ticketCanvas');
    const emptyMessage = document.getElementById('canvasEmpty');
    const propertiesPanel = document.getElementById('propertiesPanel');
    const deleteButton = document.getElementById('deleteElementBtn');
    const hiddenInput = document.getElementById('ticket_design');
    const form = document.getElementById('ticketDesignForm');
    const categorySelect = document.getElementById('ticket_category_id');
    const backgroundInput = document.getElementById('background_image');
    const removeBackgroundInput = document.getElementById('remove_background');
    const fileName = document.getElementById('uploadFileName');
    const backgroundStatus = document.getElementById('backgroundStatus');
    const dropZone = document.getElementById('backgroundDropZone');
    const saveButton = document.getElementById('saveDesignBtn');

    const categoryData = {{ \Illuminate\Support\Js::from(
        $categories->mapWithKeys(function ($category) {
            $design = $category->ticket_design;
            $design = is_array($design) ? $design : [];

            $background = $design['background'] ?? null;

            $backgroundUrl = null;

            if ($background) {
                $normalizedPath = preg_replace(
                    '#^(public/|storage/)#',
                    '',
                    ltrim($background, '/')
                );

                $backgroundUrl = \Illuminate\Support\Facades\Storage::disk('public')
                    ->url($normalizedPath);
            }

            return [
                $category->id => [
                    'design' => $design,
                    'backgroundUrl' => $backgroundUrl,
                ]
            ];
        })->all()
    ) }};

    const labels = {
        qr: 'QR CODE',
        attendee_name: 'ATTENDEE NAME',
        phone: 'PHONE NUMBER',
        ticket_number: 'TICKET NUMBER',
        bib_number: 'BIB NUMBER',
        event_name: 'EVENT NAME'
    };

    let elements = [];
    let selectedId = null;
    let nextId = 1;
    let dragState = null;
    let savedBackgroundUrl = null;
    let savedBackgroundPath = null;
    let currentBackgroundUrl = null;
    let currentBackgroundPath = null;
    let temporaryObjectUrl = null;
    let removeSavedBackground = false;

    function setBackgroundStatus(message, isError = false) {
        backgroundStatus.replaceChildren();

        const icon = document.createElement('i');
        icon.className = isError
            ? 'fa-solid fa-circle-exclamation'
            : 'fa-solid fa-circle-info';

        const text = document.createElement('span');
        text.textContent = message;

        backgroundStatus.append(icon, text);
    }

    function revokeTemporaryUrl() {
        if (temporaryObjectUrl) {
            URL.revokeObjectURL(temporaryObjectUrl);
            temporaryObjectUrl = null;
        }
    }

    function updateCanvasBackground() {
        if (currentBackgroundUrl) {
            canvas.style.backgroundImage =
                'url("' + currentBackgroundUrl.replace(/["\\]/g, '\\$&') + '")';
            canvas.style.backgroundSize = '100% 100%';
            canvas.style.backgroundPosition = 'center';
            canvas.style.backgroundRepeat = 'no-repeat';
            canvas.style.backgroundColor = '#ffffff';
        } else {
            canvas.style.backgroundColor = '#ffffff';
            canvas.style.backgroundImage =
                'linear-gradient(rgba(102,65,184,.045) 1px, transparent 1px), ' +
                'linear-gradient(90deg, rgba(102,65,184,.045) 1px, transparent 1px)';
            canvas.style.backgroundSize = '20px 20px';
            canvas.style.backgroundPosition = '0 0';
            canvas.style.backgroundRepeat = 'repeat';
        }

        if (currentBackgroundUrl) {
            setBackgroundStatus(
                removeSavedBackground
                    ? 'Background removed. Save design to confirm.'
                    : (backgroundInput.files.length
                        ? 'New background selected. Save design to upload it.'
                        : 'Saved background loaded.')
            );
        } else {
            setBackgroundStatus(
                removeSavedBackground
                    ? 'Background will be removed when you save.'
                    : 'No background loaded.'
            );
        }
    }

    function defaults(type, index) {
        const base = {
            id: 'element-' + Date.now() + '-' + index,
            type,
            x: 50 + (index * 20),
            y: 50 + (index * 15),
            width: type === 'qr' ? 130 : 280,
            height: type === 'qr' ? 130 : 48,
            fontSize: type === 'attendee_name' ? 30 : 24,
            color: '#222222',
            rotation: 0
        };

        if (type === 'phone') base.y = 115;
        if (type === 'ticket_number') base.y = 180;
        if (type === 'bib_number') base.y = 245;
        if (type === 'event_name') base.y = 310;

        return base;
    }

    function normalizeElement(element, index) {
        const fallback = defaults(element.type, index);

        return {
            ...fallback,
            ...element,
            id: element.id || fallback.id,
            x: Number(element.x ?? fallback.x),
            y: Number(element.y ?? fallback.y),
            width: Number(element.width ?? fallback.width),
            height: Number(element.height ?? fallback.height),
            fontSize: Number(element.fontSize ?? fallback.fontSize),
            rotation: Number(element.rotation ?? fallback.rotation),
            color: element.color || fallback.color
        };
    }

    function selectedElement() {
        return elements.find(element => element.id === selectedId) || null;
    }

    function clampElement(element) {
        element.width = Math.max(1, Math.min(1600, Number(element.width) || 1));
        element.height = Math.max(1, Math.min(517, Number(element.height) || 1));
        element.x = Math.max(0, Math.min(1600 - element.width, Number(element.x) || 0));
        element.y = Math.max(0, Math.min(517 - element.height, Number(element.y) || 0));
        element.fontSize = Math.max(6, Math.min(120, Number(element.fontSize) || 24));
        element.rotation = Math.max(-360, Math.min(360, Number(element.rotation) || 0));
    }

    function render() {
        canvas.querySelectorAll('.ticket-element').forEach(node => node.remove());

        if (emptyMessage) {
            emptyMessage.style.display = elements.length ? 'none' : 'flex';
        }

        elements.forEach(element => {
            const node = document.createElement('div');

            node.className = 'ticket-element ' + element.type +
                (element.id === selectedId ? ' selected' : '');

            node.dataset.id = element.id;
            node.style.left = element.x + 'px';
            node.style.top = element.y + 'px';
            node.style.width = element.width + 'px';
            node.style.height = element.height + 'px';
            node.style.fontSize = element.fontSize + 'px';
            node.style.color = element.color;
            node.style.transform = 'rotate(' + element.rotation + 'deg)';

            const label = document.createElement('span');
            label.className = 'element-label';
            label.textContent = element.type === 'qr'
                ? '▦ QR CODE'
                : (labels[element.type] || element.type);

            node.appendChild(label);

            node.addEventListener('pointerdown', startDrag);
            node.addEventListener('click', function (event) {
                event.stopPropagation();
                selectedId = element.id;
                render();
                renderProperties();
            });

            canvas.appendChild(node);
        });

        deleteButton.disabled = !selectedElement();
    }

    function field(label, property, value, type, attributes = {}) {
        const wrapper = document.createElement('div');
        const fieldId = 'prop-' + property;

        const labelNode = document.createElement('label');
        labelNode.className = 'form-label';
        labelNode.htmlFor = fieldId;
        labelNode.textContent = label;

        const input = document.createElement('input');
        input.id = fieldId;
        input.className = 'form-control';
        input.type = type;
        input.value = value ?? '';

        Object.entries(attributes).forEach(([key, val]) => {
            input.setAttribute(key, val);
        });

        input.addEventListener('input', function () {
            const element = selectedElement();
            if (!element) return;

            if (type === 'color') {
                element[property] = input.value;
            } else {
                if (input.value === '') return;

                const number = Number(input.value);
                if (!Number.isFinite(number)) return;

                element[property] = number;
            }

            clampElement(element);

            if (property === 'rotation') {
                const slider = propertiesPanel.querySelector('#prop-rotation-slider');
                const rotationValue = propertiesPanel.querySelector('#prop-rotation-value');

                if (slider) slider.value = element.rotation;
                if (rotationValue) rotationValue.textContent = element.rotation + '°';
            }

            render();
        });

        wrapper.append(labelNode, input);
        propertiesPanel.appendChild(wrapper);
    }

    function rangeField(label, property, value, min, max, step = 1) {
        const wrapper = document.createElement('div');
        wrapper.style.marginBottom = '14px';

        const header = document.createElement('div');
        header.style.display = 'flex';
        header.style.justifyContent = 'space-between';
        header.style.alignItems = 'center';
        header.style.marginBottom = '6px';

        const labelNode = document.createElement('label');
        labelNode.className = 'form-label';
        labelNode.style.margin = '0';
        labelNode.textContent = label;

        const valueLabel = document.createElement('span');
        valueLabel.id = 'prop-' + property + '-value';
        valueLabel.style.fontSize = '10px';
        valueLabel.style.fontWeight = '800';
        valueLabel.style.color = '#7045cb';
        valueLabel.textContent = value + '°';

        header.append(labelNode, valueLabel);

        const slider = document.createElement('input');
        slider.id = 'prop-' + property + '-slider';
        slider.type = 'range';
        slider.min = min;
        slider.max = max;
        slider.step = step;
        slider.value = value;
        slider.style.width = '100%';
        slider.style.accentColor = '#7045cb';
        slider.style.cursor = 'pointer';

        slider.addEventListener('input', function () {
            const element = selectedElement();
            if (!element) return;

            element[property] = Number(slider.value);
            clampElement(element);

            valueLabel.textContent = element[property] + '°';

            const numericInput = propertiesPanel.querySelector('#prop-' + property);
            if (numericInput) numericInput.value = element[property];

            const node = canvas.querySelector(
                '[data-id="' + element.id + '"]'
            );

            if (node) {
                node.style.transform =
                    'rotate(' + element.rotation + 'deg)';
            }
        });

        wrapper.append(header, slider);
        propertiesPanel.appendChild(wrapper);
    }

    function renderProperties() {
        propertiesPanel.replaceChildren();

        const element = selectedElement();

        if (!element) {
            const empty = document.createElement('div');
            empty.className = 'properties-empty';

            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-arrow-pointer';

            const message = document.createElement('p');
            message.textContent =
                'Select an element on the canvas to customize its position, size and style.';

            empty.append(icon, message);
            propertiesPanel.appendChild(empty);
            deleteButton.disabled = true;
            return;
        }

        const heading = document.createElement('div');
        heading.className = 'fw-bold';
        heading.textContent = labels[element.type] || element.type;
        propertiesPanel.appendChild(heading);

        field('Horizontal position (X)', 'x', element.x, 'number', {
            min: 0, max: 1599, step: 1
        });

        field('Vertical position (Y)', 'y', element.y, 'number', {
            min: 0, max: 516, step: 1
        });

        field('Width', 'width', element.width, 'number', {
            min: 1, max: 1600, step: 1
        });

        field('Height', 'height', element.height, 'number', {
            min: 1, max: 517, step: 1
        });

        field('Font size (px)', 'fontSize', element.fontSize, 'number', {
            min: 6, max: 120, step: 1
        });

        field('Text color', 'color', element.color, 'color');

        field('Rotation (degrees)', 'rotation', element.rotation, 'number', {
            min: -360, max: 360, step: 1
        });

        rangeField(
            'Rotate element',
            'rotation',
            element.rotation,
            -360,
            360,
            1
        );

        deleteButton.disabled = false;
    }

    function startDrag(event) {
        if (event.button !== 0) return;

        const element = elements.find(
            item => item.id === event.currentTarget.dataset.id
        );

        if (!element) return;

        selectedId = element.id;

        dragState = {
            id: element.id,
            startX: event.clientX,
            startY: event.clientY,
            originalX: element.x,
            originalY: element.y
        };

        try {
            event.currentTarget.setPointerCapture(event.pointerId);
        } catch (error) {}

        render();
        renderProperties();
        event.preventDefault();
    }

    document.addEventListener('pointermove', function (event) {
        if (!dragState) return;

        const element = elements.find(item => item.id === dragState.id);
        if (!element) return;

        const rect = canvas.getBoundingClientRect();
        const scaleX = rect.width / 1600;
        const scaleY = rect.height / 517;

        if (!scaleX || !scaleY) return;

        element.x = dragState.originalX +
            ((event.clientX - dragState.startX) / scaleX);

        element.y = dragState.originalY +
            ((event.clientY - dragState.startY) / scaleY);

        clampElement(element);
        render();
    });

    document.addEventListener('pointerup', function () {
        dragState = null;
    });

    document.addEventListener('pointercancel', function () {
        dragState = null;
    });

    canvas.addEventListener('click', function (event) {
        if (
            event.target === canvas ||
            event.target === emptyMessage ||
            (emptyMessage && emptyMessage.contains(event.target))
        ) {
            selectedId = null;
            render();
            renderProperties();
        }
    });

    document.querySelectorAll('.add-element').forEach(button => {
        button.addEventListener('click', function () {
            const element = defaults(button.dataset.type, nextId++);

            elements.push(element);
            selectedId = element.id;

            render();
            renderProperties();
        });
    });

    deleteButton.addEventListener('click', function () {
        if (!selectedId) return;

        elements = elements.filter(element => element.id !== selectedId);
        selectedId = null;

        render();
        renderProperties();
    });

    function loadSelectedCategory() {
        const data = categoryData[categorySelect.value] || {};
        const saved = data.design || {};

        elements = Array.isArray(saved.elements)
            ? saved.elements.map(normalizeElement)
            : [];

        savedBackgroundPath = saved.background || null;
        savedBackgroundUrl = data.backgroundUrl || null;

        currentBackgroundPath = savedBackgroundPath;
        currentBackgroundUrl = savedBackgroundUrl;

        removeSavedBackground = false;

        revokeTemporaryUrl();

        backgroundInput.value = '';
        fileName.textContent = 'No new image selected';
        removeBackgroundInput.value = '0';

        selectedId = null;
        dragState = null;

        updateCanvasBackground();
        render();
        renderProperties();
    }

    backgroundInput.addEventListener('change', function () {
        const file = backgroundInput.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png'];
        const maxSize = 5 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            backgroundInput.value = '';
            setBackgroundStatus('Please choose a JPG, JPEG or PNG image.', true);
            return;
        }

        if (file.size > maxSize) {
            backgroundInput.value = '';
            setBackgroundStatus('The image must be 5 MB or smaller.', true);
            return;
        }

        revokeTemporaryUrl();

        temporaryObjectUrl = URL.createObjectURL(file);
        currentBackgroundUrl = temporaryObjectUrl;
        currentBackgroundPath = savedBackgroundPath;

        removeSavedBackground = false;
        removeBackgroundInput.value = '0';

        fileName.textContent = file.name;
        updateCanvasBackground();
    });

    document.getElementById('removeBackgroundBtn').addEventListener('click', function () {
        if (
            !currentBackgroundUrl &&
            !savedBackgroundPath &&
            !backgroundInput.files.length
        ) {
            setBackgroundStatus('There is no background to remove.');
            return;
        }

        revokeTemporaryUrl();

        currentBackgroundUrl = null;
        currentBackgroundPath = null;
        removeSavedBackground = true;

        removeBackgroundInput.value = '1';
        backgroundInput.value = '';
        fileName.textContent = 'Background marked for removal';

        updateCanvasBackground();
    });

    document.getElementById('restoreBackgroundBtn').addEventListener('click', function () {
        revokeTemporaryUrl();

        backgroundInput.value = '';
        fileName.textContent = 'No new image selected';

        removeSavedBackground = false;
        removeBackgroundInput.value = '0';

        currentBackgroundPath = savedBackgroundPath;
        currentBackgroundUrl = savedBackgroundUrl;

        updateCanvasBackground();
    });

    dropZone.addEventListener('dragover', function (event) {
        event.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', function () {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', function (event) {
        event.preventDefault();
        dropZone.classList.remove('drag-over');

        const file = event.dataTransfer.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png'];

        if (!allowedTypes.includes(file.type)) {
            setBackgroundStatus('Please drop a JPG, JPEG or PNG image.', true);
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            setBackgroundStatus('The image must be 5 MB or smaller.', true);
            return;
        }

        const transfer = new DataTransfer();
        transfer.items.add(file);

        backgroundInput.files = transfer.files;
        backgroundInput.dispatchEvent(
            new Event('change', {bubbles: true})
        );
    });

    document.getElementById('loadDesignBtn').addEventListener('click', function () {
        loadSelectedCategory();
    });

    document.getElementById('resetDesignBtn').addEventListener('click', function () {
        if (!confirm('Clear the current layout? This will not delete the saved design until you save.')) {
            return;
        }

        elements = [];
        selectedId = null;

        render();
        renderProperties();
    });

    let previousCategoryId = categorySelect.value;

    categorySelect.addEventListener('change', function () {
        const newCategoryId = categorySelect.value;

        if (!confirm('Load this category design? Unsaved changes will be lost.')) {
            categorySelect.value = previousCategoryId;
            return;
        }

        previousCategoryId = newCategoryId;
        loadSelectedCategory();
    });

    form.addEventListener('submit', function (event) {
        if (!elements.length) {
            event.preventDefault();
            alert('Add at least one element before saving the design.');
            return;
        }

        if (
            backgroundInput.files.length &&
            !['image/jpeg', 'image/png'].includes(backgroundInput.files[0].type)
        ) {
            event.preventDefault();
            alert('Please choose a JPG, JPEG or PNG background.');
            return;
        }

        hiddenInput.value = JSON.stringify({
            version: 1,
            canvas: {
                width: 1600,
                height: 517
            },
            background: removeSavedBackground ? null : currentBackgroundPath,
            elements: elements.map(element => ({
                id: element.id,
                type: element.type,
                x: element.x,
                y: element.y,
                width: element.width,
                height: element.height,
                fontSize: element.fontSize,
                color: element.color,
                rotation: element.rotation
            }))
        });

        saveButton.disabled = true;
        saveButton.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    });

    loadSelectedCategory();
});
</script>
@endif
@endsection
