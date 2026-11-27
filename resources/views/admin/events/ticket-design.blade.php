@extends('layouts.admin')

@section('title', 'Ticket Design Studio')

@section('content')
<div class="container-fluid py-4 ticket-studio">

    {{-- =========================================================
         PAGE HEADER
    ========================================================= --}}
    <header class="ts-header">
        <a href="{{ route('admin.events.index') }}" class="ts-back">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to events
        </a>
        <h1>Ticket design studio</h1>
        <p>Design the ticket for <strong>{{ $event->title }}</strong>. Each ticket category keeps its own layout.</p>
    </header>

    @if(session('success'))
        <div class="alert alert-success ts-alert" role="status">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger ts-alert" role="alert">
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

        <div class="ts-card ts-empty-page">
            <i class="fa-solid fa-ticket" aria-hidden="true"></i>
            <h2>No ticket categories yet</h2>
            <p>Create a ticket category before designing its ticket.</p>
            <a href="{{ route('admin.events.index') }}" class="ts-btn ts-btn-primary">Back to events</a>
        </div>

    @else

        <form id="ticketDesignForm"
              method="POST"
              enctype="multipart/form-data"
              action="{{ route('admin.events.ticket-design.save', $event) }}">
            @csrf

            <input type="hidden" name="ticket_design" id="ticket_design">
            <input type="hidden" name="remove_background" id="remove_background" value="0">

            {{-- =====================================================
                 TOP BAR: category + actions
            ====================================================== --}}
            <section class="ts-card ts-bar" aria-label="Design settings">

                <div class="ts-bar-category">
                    <label for="ticket_category_id">Ticket category</label>
                    <select id="ticket_category_id" name="ticket_category_id" class="form-select">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected((string) old('ticket_category_id', $selectedCategory?->id) === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="ts-bar-actions">
                    <span class="ts-state" id="saveState" role="status" aria-live="polite">
                        <span class="ts-state-dot"></span>
                        <span id="saveStateText">All changes saved</span>
                    </span>

                    <button type="button" class="ts-btn ts-btn-ghost" id="loadDesignBtn">
                        <i class="fa-solid fa-rotate" aria-hidden="true"></i> Reload saved
                    </button>
                    <button type="button" class="ts-btn ts-btn-ghost" id="resetDesignBtn">
                        <i class="fa-solid fa-eraser" aria-hidden="true"></i> Clear layout
                    </button>
                    <button type="submit" class="ts-btn ts-btn-primary" id="saveDesignBtn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Save design
                    </button>
                </div>

            </section>

            <div class="ts-layout">

                {{-- =================================================
                     CANVAS AREA
                ================================================== --}}
                <section class="ts-card ts-stage" id="studioStage" aria-label="Design canvas">

                    <div class="ts-addbar" role="group" aria-label="Add an element">
                        <span class="ts-addbar-label">Add to ticket</span>

                        <button type="button" class="ts-chip add-element" data-type="qr">
                            <i class="fa-solid fa-qrcode" aria-hidden="true"></i> QR code
                        </button>
                        <button type="button" class="ts-chip add-element" data-type="attendee_name">
                            <i class="fa-solid fa-user" aria-hidden="true"></i> Attendee name
                        </button>
                        <button type="button" class="ts-chip add-element" data-type="phone">
                            <i class="fa-solid fa-phone" aria-hidden="true"></i> Phone number
                        </button>
                        <button type="button" class="ts-chip add-element" data-type="ticket_number">
                            <i class="fa-solid fa-ticket" aria-hidden="true"></i> Ticket number
                        </button>
                        <button type="button" class="ts-chip add-element" data-type="bib_number">
                            <i class="fa-solid fa-hashtag" aria-hidden="true"></i> Bib number
                        </button>
                        <button type="button" class="ts-chip add-element" data-type="event_name">
                            <i class="fa-solid fa-flag-checkered" aria-hidden="true"></i> Event name
                        </button>
                    </div>

                    <div class="ts-viewport" id="canvasViewport">
                        <div class="ts-sizer" id="canvasSizer">
                            <div id="ticketCanvas" aria-label="Ticket design canvas">

                                <div class="canvas-grid" id="canvasGrid"></div>

                                <div class="canvas-empty" id="canvasEmpty">
                                    <div>
                                        <strong>Start your ticket</strong>
                                        <span>Upload artwork on the right, then add elements from the bar above.</span>
                                        <button type="button" class="ts-btn ts-btn-soft" id="quickStartBtn">
                                            <i class="fa-solid fa-bolt" aria-hidden="true"></i> Add a starter layout
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="ts-controls">
                        <div class="ts-toggles">
                            <label class="ts-check">
                                <input type="checkbox" id="gridToggle" checked> Show grid
                            </label>
                            <label class="ts-check">
                                <input type="checkbox" id="snapToggle" checked> Snap to grid
                            </label>
                        </div>

                        <div class="ts-zoom" role="group" aria-label="Zoom">
                            <button type="button" class="ts-icon-btn" id="zoomOutBtn" aria-label="Zoom out">
                                <i class="fa-solid fa-minus" aria-hidden="true"></i>
                            </button>
                            <span id="zoomLabel" class="ts-zoom-label">100%</span>
                            <button type="button" class="ts-icon-btn" id="zoomInBtn" aria-label="Zoom in">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="ts-btn ts-btn-ghost ts-btn-sm" id="zoomFitBtn">Fit</button>
                        </div>
                    </div>

                    <div class="ts-layers" id="layersWrap" hidden>
                        <span class="ts-layers-label">On this ticket</span>
                        <div class="ts-layer-list" id="layerList"></div>
                    </div>

                    <p class="ts-help">
                        Drag an element to move it, drag its corners to resize it, or use the arrow keys
                        (hold Shift for bigger steps). Press Delete to remove it. The text shown is sample
                        data, so each ticket prints the real guest details.
                    </p>

                </section>

                {{-- =================================================
                     SIDE PANEL
                ================================================== --}}
                <aside class="ts-side">

                    {{-- Selected element --}}
                    <section class="ts-card ts-panel" aria-label="Selected element">

                        <div class="ts-panel-head">
                            <h2 id="inspectorHeading">Selected element</h2>
                        </div>

                        <div class="ts-inspector-empty" id="inspectorEmpty">
                            <i class="fa-solid fa-arrow-pointer" aria-hidden="true"></i>
                            <p>Select an element on the canvas to change its position, size and style.</p>
                        </div>

                        <div id="inspectorBody" hidden>
                            <div class="ts-inspector-top">
                                <span class="ts-inspector-type" id="inspectorType"></span>
                                <div class="ts-inspector-actions">
                                    <button type="button" class="ts-btn ts-btn-ghost ts-btn-sm" id="duplicateElementBtn">
                                        <i class="fa-regular fa-clone" aria-hidden="true"></i> Duplicate
                                    </button>
                                    <button type="button" class="ts-btn ts-btn-danger ts-btn-sm" id="deleteElementBtn" disabled>
                                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Delete
                                    </button>
                                </div>
                            </div>

                            <div id="propertiesPanel"></div>
                        </div>

                    </section>

                    {{-- Background --}}
                    <section class="ts-card ts-panel" aria-label="Ticket background">

                        <div class="ts-panel-head">
                            <h2>Ticket background</h2>
                        </div>

                        <div class="ts-drop" id="backgroundDropZone">
                            <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                            <div>
                                <strong>Drop your artwork here</strong>
                                <span>JPG or PNG, up to 5 MB. Best at 1600 × 517 px.</span>
                            </div>
                            <button type="button" class="ts-btn ts-btn-soft" id="chooseImageBtn">
                                <i class="fa-solid fa-folder-open" aria-hidden="true"></i> Choose image
                            </button>
                            <input type="file"
                                   id="background_image"
                                   name="background_image"
                                   accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                   hidden>
                            <small id="uploadFileName" class="ts-filename">No new image selected</small>
                        </div>

                        <div class="ts-bg-actions">
                            <button type="button" class="ts-btn ts-btn-ghost" id="removeBackgroundBtn">
                                <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Remove
                            </button>
                            <button type="button" class="ts-btn ts-btn-ghost" id="restoreBackgroundBtn">
                                <i class="fa-solid fa-rotate-left" aria-hidden="true"></i> Restore saved
                            </button>
                        </div>

                        <div class="ts-bg-status" id="backgroundStatus" role="status">
                            <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                            <span>No background loaded.</span>
                        </div>

                    </section>

                    <div class="ts-tip">
                        <i class="fa-solid fa-lightbulb" aria-hidden="true"></i>
                        <p>Keep the QR code square and leave empty space around it so it scans reliably.</p>
                    </div>

                </aside>

            </div>
        </form>
    @endif
</div>

{{-- =========================================================
     STYLES
========================================================= --}}
<style>
.ticket-studio {
    --ts-accent: #5b34c2;
    --ts-accent-dark: #452398;
    --ts-accent-soft: #f1ecfd;
    --ts-ink: #231a36;
    --ts-muted: #6c637e;
    --ts-line: #e5e1ee;
    --ts-bg: #f6f4fa;
    --ts-danger: #b4233f;
    color: var(--ts-ink);
    font-size: 14px;
    padding-bottom: 40px !important;
}
.ticket-studio *, .ticket-studio *::before, .ticket-studio *::after { box-sizing: border-box; }
.ticket-studio [hidden] { display: none !important; }

/* ---------- Header ---------- */
.ticket-studio .ts-header { margin-bottom: 20px; }
.ticket-studio .ts-back {
    display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px;
    color: var(--ts-muted); font-weight: 600; text-decoration: none;
}
.ticket-studio .ts-back:hover { color: var(--ts-accent); }
.ticket-studio .ts-header h1 { margin: 0 0 6px; font-size: 26px; font-weight: 750; letter-spacing: -.4px; }
.ticket-studio .ts-header p { margin: 0; color: var(--ts-muted); max-width: 70ch; }
.ticket-studio .ts-header p strong { color: var(--ts-ink); }
.ticket-studio .ts-alert { border-radius: 10px; margin-bottom: 18px; }

/* ---------- Cards ---------- */
.ticket-studio .ts-card {
    background: #fff; border: 1px solid var(--ts-line); border-radius: 14px;
}
.ticket-studio .ts-empty-page { padding: 56px 20px; text-align: center; }
.ticket-studio .ts-empty-page > i {
    display: inline-grid; place-items: center; width: 64px; height: 64px;
    margin-bottom: 16px; border-radius: 16px; font-size: 26px;
    color: var(--ts-accent); background: var(--ts-accent-soft);
}
.ticket-studio .ts-empty-page h2 { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
.ticket-studio .ts-empty-page p { color: var(--ts-muted); margin-bottom: 18px; }

/* ---------- Buttons ---------- */
.ticket-studio .ts-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: 40px; padding: 8px 14px; border: 1px solid transparent;
    border-radius: 9px; font-size: 14px; font-weight: 650; line-height: 1.2;
    text-decoration: none; cursor: pointer; white-space: nowrap;
    transition: background-color .15s, border-color .15s, color .15s;
}
.ticket-studio .ts-btn-sm { min-height: 34px; padding: 6px 11px; font-size: 13px; }
.ticket-studio .ts-btn-primary { color: #fff; background: var(--ts-accent); }
.ticket-studio .ts-btn-primary:hover { color: #fff; background: var(--ts-accent-dark); }
.ticket-studio .ts-btn-soft { color: var(--ts-accent-dark); background: var(--ts-accent-soft); border-color: #ddd1fa; }
.ticket-studio .ts-btn-soft:hover { background: #e7defb; }
.ticket-studio .ts-btn-ghost { color: #4d4360; background: #fff; border-color: var(--ts-line); }
.ticket-studio .ts-btn-ghost:hover { background: var(--ts-bg); }
.ticket-studio .ts-btn-danger { color: var(--ts-danger); background: #fff; border-color: #efd3da; }
.ticket-studio .ts-btn-danger:hover:not(:disabled) { background: #fdf0f3; }
.ticket-studio .ts-btn:disabled { opacity: .5; cursor: not-allowed; }
.ticket-studio .ts-icon-btn {
    display: inline-grid; place-items: center; width: 34px; height: 34px;
    border: 1px solid var(--ts-line); border-radius: 8px; background: #fff;
    color: #4d4360; cursor: pointer;
}
.ticket-studio .ts-icon-btn:hover { background: var(--ts-bg); }
.ticket-studio .ts-btn:focus-visible,
.ticket-studio .ts-icon-btn:focus-visible,
.ticket-studio .ts-chip:focus-visible,
.ticket-studio .ts-layer:focus-visible,
.ticket-studio .ts-swatch:focus-visible {
    outline: 3px solid #b9a2f3; outline-offset: 2px;
}

/* ---------- Top bar ---------- */
.ticket-studio .ts-bar {
    display: flex; align-items: flex-end; justify-content: space-between;
    flex-wrap: wrap; gap: 14px 20px; padding: 16px 18px; margin-bottom: 20px;
}
.ticket-studio .ts-bar-category { flex: 1 1 240px; max-width: 360px; }
.ticket-studio .ts-bar-category label { display: block; margin-bottom: 6px; font-weight: 650; color: #4d4360; }
.ticket-studio .ts-bar-category .form-select {
    min-height: 42px; border-color: var(--ts-line); border-radius: 9px;
    font-size: 14px; font-weight: 600; box-shadow: none;
}
.ticket-studio .ts-bar-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }
.ticket-studio .ts-state {
    display: inline-flex; align-items: center; gap: 8px; padding: 7px 12px;
    border-radius: 999px; background: #eaf7f1; color: #17694a; font-weight: 650; font-size: 13px;
}
.ticket-studio .ts-state-dot { width: 8px; height: 8px; border-radius: 50%; background: #25a574; }
.ticket-studio .ts-state.is-dirty { background: #fff4e0; color: #8a5200; }
.ticket-studio .ts-state.is-dirty .ts-state-dot { background: #e69a14; }

/* ---------- Layout ---------- */
.ticket-studio .ts-layout {
    display: grid; grid-template-columns: minmax(0, 1fr) 340px;
    gap: 20px; align-items: start;
}
.ticket-studio .ts-side { display: flex; flex-direction: column; gap: 16px; min-width: 0; }

/* ---------- Stage ---------- */
.ticket-studio .ts-stage { min-width: 0; overflow: hidden; }
.ticket-studio .ts-addbar {
    display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
    padding: 14px 16px; border-bottom: 1px solid var(--ts-line);
}
.ticket-studio .ts-addbar-label { margin-right: 4px; font-weight: 700; }
.ticket-studio .ts-chip {
    display: inline-flex; align-items: center; gap: 8px; min-height: 38px;
    padding: 6px 13px; border: 1px solid var(--ts-line); border-radius: 999px;
    background: #fff; color: #3d3350; font-size: 13.5px; font-weight: 650; cursor: pointer;
    transition: background-color .15s, border-color .15s;
}
.ticket-studio .ts-chip i { color: var(--ts-accent); font-size: 13px; }
.ticket-studio .ts-chip:hover { background: var(--ts-accent-soft); border-color: #cdbcf6; }

.ticket-studio .ts-viewport {
    padding: 12px; overflow: auto; background-color: #ece9f2;
    background-image: radial-gradient(#d4cfe0 1px, transparent 1px);
    background-size: 14px 14px;
}
.ticket-studio .ts-sizer { position: relative; margin: 0 auto; }
.ticket-studio #ticketCanvas {
    --s: 1;
    position: absolute; top: 0; left: 0; width: 1600px; height: 517px;
    overflow: hidden; background-color: #fff; transform-origin: top left;
    box-shadow: 0 6px 22px rgba(35, 26, 54, .18);
}
.ticket-studio .canvas-grid {
    position: absolute; inset: 0; pointer-events: none; z-index: 1;
    background-image:
        linear-gradient(rgba(91, 52, 194, .09) 1px, transparent 1px),
        linear-gradient(90deg, rgba(91, 52, 194, .09) 1px, transparent 1px);
    background-size: 20px 20px;
}
.ticket-studio .canvas-grid.is-off { display: none; }
.ticket-studio .canvas-empty {
    position: absolute; inset: 0; z-index: 2; display: flex; align-items: center;
    justify-content: center; text-align: center; pointer-events: none;
}
.ticket-studio .canvas-empty > div {
    display: flex; flex-direction: column; align-items: center; gap: 14px;
    max-width: 640px; padding: 30px; border-radius: 16px; background: rgba(255, 255, 255, .92);
}
.ticket-studio .canvas-empty strong { font-size: 34px; font-weight: 750; color: #3b2d57; }
.ticket-studio .canvas-empty span { font-size: 24px; color: #6c637e; line-height: 1.4; }
.ticket-studio .canvas-empty .ts-btn { pointer-events: auto; font-size: 22px; min-height: 60px; padding: 12px 24px; border-radius: 14px; }

/* Elements on the canvas */
.ticket-studio .ticket-element {
    position: absolute; z-index: 3; display: flex; align-items: center; justify-content: center;
    padding: 2px 6px; text-align: center; cursor: grab; user-select: none; touch-action: none;
    transform-origin: center center;
    outline: calc(1.5px / var(--s, 1)) dashed rgba(91, 52, 194, .55);
    outline-offset: 0;
}
.ticket-studio .ticket-element:hover { outline-color: var(--ts-accent); background: rgba(91, 52, 194, .06); }
.ticket-studio .ticket-element:active { cursor: grabbing; }
.ticket-studio .ticket-element.selected {
    z-index: 10; outline: calc(2.5px / var(--s, 1)) solid var(--ts-accent);
    background: rgba(91, 52, 194, .08);
}
.ticket-studio .ticket-element .element-label {
    display: block; width: 100%; overflow: hidden; text-overflow: ellipsis;
    white-space: nowrap; font-weight: 700; pointer-events: none; line-height: 1.2;
}
.ticket-studio .ticket-element.qr {
    padding: 0; background-color: #fff;
    background-image: conic-gradient(#1f1f1f 25%, #fff 0 50%, #1f1f1f 0 75%, #fff 0);
    background-size: 14% 14%;
    box-shadow: inset 0 0 0 10px #fff;
}
.ticket-studio .ticket-element.qr.selected { background-color: #fff; }

.ticket-studio .resize-handle {
    display: none; position: absolute; z-index: 11;
    width: calc(14px / var(--s, 1)); height: calc(14px / var(--s, 1));
    background: #fff; border: calc(2px / var(--s, 1)) solid var(--ts-accent);
    border-radius: 3px; touch-action: none;
}
.ticket-studio .ticket-element.selected:not(.rotated) .resize-handle { display: block; }
.ticket-studio .resize-handle.nw { top: 0; left: 0; transform: translate(-50%, -50%); cursor: nwse-resize; }
.ticket-studio .resize-handle.ne { top: 0; right: 0; transform: translate(50%, -50%); cursor: nesw-resize; }
.ticket-studio .resize-handle.sw { bottom: 0; left: 0; transform: translate(-50%, 50%); cursor: nesw-resize; }
.ticket-studio .resize-handle.se { bottom: 0; right: 0; transform: translate(50%, 50%); cursor: nwse-resize; }

/* Controls under the canvas */
.ticket-studio .ts-controls {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;
    gap: 10px 20px; padding: 12px 16px; border-top: 1px solid var(--ts-line);
}
.ticket-studio .ts-toggles { display: flex; flex-wrap: wrap; gap: 6px 20px; }
.ticket-studio .ts-check { display: inline-flex; align-items: center; gap: 8px; margin: 0; font-weight: 600; cursor: pointer; }
.ticket-studio .ts-check input { width: 17px; height: 17px; accent-color: var(--ts-accent); cursor: pointer; }
.ticket-studio .ts-zoom { display: inline-flex; align-items: center; gap: 8px; }
.ticket-studio .ts-zoom-label { min-width: 44px; text-align: center; font-weight: 700; font-variant-numeric: tabular-nums; }

.ticket-studio .ts-layers {
    display: flex; align-items: flex-start; flex-wrap: wrap; gap: 8px 12px;
    padding: 12px 16px; border-top: 1px solid var(--ts-line);
}
.ticket-studio .ts-layers-label { padding-top: 7px; font-weight: 700; }
.ticket-studio .ts-layer-list { display: flex; flex-wrap: wrap; gap: 8px; flex: 1 1 200px; }
.ticket-studio .ts-layer {
    display: inline-flex; align-items: center; gap: 7px; min-height: 34px; padding: 5px 11px;
    border: 1px solid var(--ts-line); border-radius: 8px; background: var(--ts-bg);
    color: #3d3350; font-size: 13px; font-weight: 600; cursor: pointer;
}
.ticket-studio .ts-layer i { color: var(--ts-accent); font-size: 12px; }
.ticket-studio .ts-layer:hover { border-color: #cdbcf6; }
.ticket-studio .ts-layer.is-selected { background: var(--ts-accent-soft); border-color: var(--ts-accent); color: var(--ts-accent-dark); }
.ticket-studio .ts-help {
    margin: 0; padding: 12px 16px 16px; border-top: 1px solid var(--ts-line);
    color: var(--ts-muted); font-size: 13px; line-height: 1.6;
}

/* ---------- Side panels ---------- */
.ticket-studio .ts-panel { padding: 18px; }
.ticket-studio .ts-panel-head { margin-bottom: 14px; }
.ticket-studio .ts-panel-head h2 { margin: 0; font-size: 16px; font-weight: 700; }

.ticket-studio .ts-inspector-empty { padding: 22px 12px; text-align: center; border: 1px dashed var(--ts-line); border-radius: 10px; background: #faf9fc; }
.ticket-studio .ts-inspector-empty i { display: block; margin-bottom: 10px; font-size: 20px; color: var(--ts-accent); }
.ticket-studio .ts-inspector-empty p { margin: 0; color: var(--ts-muted); line-height: 1.6; }

.ticket-studio .ts-inspector-top { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; }
.ticket-studio .ts-inspector-type {
    display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px;
    border-radius: 8px; background: var(--ts-accent-soft); color: var(--ts-accent-dark); font-weight: 700;
}
.ticket-studio .ts-inspector-actions { display: flex; gap: 8px; }

.ticket-studio .ts-section { padding: 14px 0; border-top: 1px solid var(--ts-line); }
.ticket-studio .ts-section h3 { margin: 0 0 10px; font-size: 14px; font-weight: 700; }
.ticket-studio .ts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.ticket-studio .ts-field { margin-bottom: 10px; }
.ticket-studio .ts-field:last-child { margin-bottom: 0; }
.ticket-studio .ts-field label { display: block; margin-bottom: 5px; color: var(--ts-muted); font-size: 13px; font-weight: 600; }
.ticket-studio .ts-field .form-control {
    width: 100%; min-height: 40px; padding: 7px 10px; border: 1px solid var(--ts-line);
    border-radius: 8px; background: #fff; color: var(--ts-ink); font-size: 14px; box-shadow: none;
}
.ticket-studio .ts-field .form-control:focus { border-color: #9d80ea; box-shadow: 0 0 0 3px rgba(91, 52, 194, .15); }
.ticket-studio .ts-field input[type="range"] { width: 100%; margin: 6px 0 0; accent-color: var(--ts-accent); cursor: pointer; }
.ticket-studio .ts-center-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 10px; }
.ticket-studio .ts-note { margin: 8px 0 0; color: var(--ts-muted); font-size: 12.5px; }
.ticket-studio .ts-colors { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
.ticket-studio .ts-swatch { width: 30px; height: 30px; border: 2px solid #fff; border-radius: 50%; box-shadow: 0 0 0 1px var(--ts-line); cursor: pointer; }
.ticket-studio .ts-colors input[type="color"] { width: 40px; height: 34px; padding: 2px; border: 1px solid var(--ts-line); border-radius: 8px; background: #fff; cursor: pointer; }

/* Background */
.ticket-studio .ts-drop {
    display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 18px 14px;
    text-align: center; border: 1.5px dashed #c9b9ef; border-radius: 12px; background: #fbf9ff;
    transition: background-color .15s, border-color .15s;
}
.ticket-studio .ts-drop.drag-over { background: #f0e8ff; border-color: var(--ts-accent); }
.ticket-studio .ts-drop > i { font-size: 24px; color: var(--ts-accent); }
.ticket-studio .ts-drop strong { display: block; margin-bottom: 3px; }
.ticket-studio .ts-drop span { color: var(--ts-muted); font-size: 13px; }
.ticket-studio .ts-filename { max-width: 100%; color: var(--ts-muted); font-size: 12.5px; overflow-wrap: anywhere; }
.ticket-studio .ts-bg-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 10px; }
.ticket-studio .ts-bg-status {
    display: flex; align-items: flex-start; gap: 8px; margin-top: 10px; padding: 10px 12px;
    border-radius: 9px; background: var(--ts-bg); color: #5a5069; font-size: 13px;
    line-height: 1.5; overflow-wrap: anywhere;
}
.ticket-studio .ts-bg-status i { margin-top: 3px; color: var(--ts-accent); }
.ticket-studio .ts-bg-status.is-error { background: #fdeff2; color: #8f1d36; }
.ticket-studio .ts-bg-status.is-error i { color: var(--ts-danger); }

.ticket-studio .ts-tip { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 12px; background: var(--ts-accent-soft); }
.ticket-studio .ts-tip i { margin-top: 2px; color: var(--ts-accent); }
.ticket-studio .ts-tip p { margin: 0; color: #4a3a73; font-size: 13px; line-height: 1.55; }

/* ---------- Responsive ---------- */
@media (max-width: 1099px) {
    .ticket-studio .ts-layout { grid-template-columns: 1fr; }
}
@media (max-width: 575px) {
    .ticket-studio .ts-header h1 { font-size: 22px; }
    .ticket-studio .ts-bar { padding: 14px; }
    .ticket-studio .ts-bar-category { max-width: none; flex-basis: 100%; }
    .ticket-studio .ts-bar-actions { width: 100%; }
    .ticket-studio .ts-bar-actions .ts-state { width: 100%; justify-content: center; }
    .ticket-studio .ts-bar-actions .ts-btn { flex: 1 1 auto; }
    .ticket-studio .ts-viewport { padding: 6px; }
    .ticket-studio .ts-addbar { padding: 12px; }
    .ticket-studio .ts-addbar-label { flex-basis: 100%; }
}
@media (prefers-reduced-motion: reduce) {
    .ticket-studio * { transition: none !important; }
}
</style>

{{-- =========================================================
     SCRIPT
========================================================= --}}
@if(isset($categories) && $categories->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const W = 1600;
    const H = 517;
    const GRID = 10;
    const ALLOWED_IMAGES = ['image/jpeg', 'image/png'];
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024;

    const $ = id => document.getElementById(id);

    const canvas = $('ticketCanvas');
    const sizer = $('canvasSizer');
    const viewport = $('canvasViewport');
    const canvasGrid = $('canvasGrid');
    const emptyMessage = $('canvasEmpty');
    const propertiesPanel = $('propertiesPanel');
    const inspectorEmpty = $('inspectorEmpty');
    const inspectorBody = $('inspectorBody');
    const inspectorType = $('inspectorType');
    const deleteButton = $('deleteElementBtn');
    const duplicateButton = $('duplicateElementBtn');
    const hiddenInput = $('ticket_design');
    const form = $('ticketDesignForm');
    const categorySelect = $('ticket_category_id');
    const backgroundInput = $('background_image');
    const removeBackgroundInput = $('remove_background');
    const fileName = $('uploadFileName');
    const backgroundStatus = $('backgroundStatus');
    const dropZone = $('backgroundDropZone');
    const saveButton = $('saveDesignBtn');
    const saveState = $('saveState');
    const saveStateText = $('saveStateText');
    const layersWrap = $('layersWrap');
    const layerList = $('layerList');
    const zoomLabel = $('zoomLabel');
    const stage = $('studioStage');

    const eventTitle = {{ \Illuminate\Support\Js::from($event->title) }};

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

    const TYPES = {
        qr:            { label: 'QR code',       icon: 'fa-qrcode',         sample: '' },
        attendee_name: { label: 'Attendee name', icon: 'fa-user',           sample: 'Alex Tan' },
        phone:         { label: 'Phone number',  icon: 'fa-phone',          sample: '+65 9123 4567' },
        ticket_number: { label: 'Ticket number', icon: 'fa-ticket',         sample: 'EVT-000123' },
        bib_number:    { label: 'Bib number',    icon: 'fa-hashtag',        sample: '1024' },
        event_name:    { label: 'Event name',    icon: 'fa-flag-checkered', sample: eventTitle }
    };

    const SLOTS = {
        qr:            { x: 60,  y: 60,  w: 160, h: 160, fs: 24 },
        attendee_name: { x: 260, y: 60,  w: 620, h: 64,  fs: 40 },
        phone:         { x: 260, y: 140, w: 420, h: 44,  fs: 26 },
        ticket_number: { x: 260, y: 200, w: 420, h: 44,  fs: 26 },
        bib_number:    { x: 260, y: 260, w: 420, h: 44,  fs: 26 },
        event_name:    { x: 260, y: 330, w: 760, h: 56,  fs: 32 }
    };

    const SWATCHES = ['#222222', '#ffffff', '#5b34c2', '#c0264b', '#1f7a4d', '#1d5fc4'];

    let elements = [];
    let selectedId = null;
    let nextId = 1;
    let drag = null;
    let refs = {};

    let dirty = false;
    let submitting = false;
    let snapEnabled = true;

    let zoomMode = 'fit';
    let zoomValue = 1;
    let currentScale = 1;

    let savedBackgroundUrl = null;
    let savedBackgroundPath = null;
    let currentBackgroundUrl = null;
    let currentBackgroundPath = null;
    let temporaryObjectUrl = null;
    let removeSavedBackground = false;

    /* ------------------------------------------------------------
       Small helpers
    ------------------------------------------------------------ */

    function newId() {
        return 'element-' + Date.now() + '-' + (nextId++);
    }

    function byId(id) {
        return elements.find(element => element.id === id) || null;
    }

    function selectedElement() {
        return byId(selectedId);
    }

    function nodeFor(id) {
        return canvas.querySelector('[data-id="' + id + '"]');
    }

    function toHex(color) {
        if (/^#[0-9a-f]{6}$/i.test(color || '')) return color;

        const short = /^#([0-9a-f])([0-9a-f])([0-9a-f])$/i.exec(color || '');
        if (short) return '#' + short[1] + short[1] + short[2] + short[2] + short[3] + short[3];

        return '#222222';
    }

    function markDirty() {
        dirty = true;
        saveState.classList.add('is-dirty');
        saveStateText.textContent = 'Unsaved changes';
    }

    function markClean() {
        dirty = false;
        saveState.classList.remove('is-dirty');
        saveStateText.textContent = 'All changes saved';
    }

    /* ------------------------------------------------------------
       Zoom
    ------------------------------------------------------------ */

    function applyScale() {
        let scale;

        if (zoomMode === 'fit') {
            const available = viewport.clientWidth - 24;
            scale = Math.min(1, Math.max(0.2, available / W));
        } else {
            scale = zoomValue;
        }

        currentScale = scale;

        canvas.style.transform = 'scale(' + scale + ')';
        canvas.style.setProperty('--s', scale);
        sizer.style.width = (W * scale) + 'px';
        sizer.style.height = (H * scale) + 'px';
        zoomLabel.textContent = Math.round(scale * 100) + '%';
    }

    function stepZoom(direction) {
        const steps = [0.25, 0.5, 0.75, 1, 1.5, 2];
        let target = currentScale;

        if (direction > 0) {
            target = steps.find(step => step > currentScale + 0.01) || steps[steps.length - 1];
        } else {
            target = [...steps].reverse().find(step => step < currentScale - 0.01) || steps[0];
        }

        zoomMode = 'manual';
        zoomValue = target;
        applyScale();
    }

    $('zoomInBtn').addEventListener('click', () => stepZoom(1));
    $('zoomOutBtn').addEventListener('click', () => stepZoom(-1));
    $('zoomFitBtn').addEventListener('click', function () {
        zoomMode = 'fit';
        applyScale();
    });

    if (window.ResizeObserver) {
        new ResizeObserver(applyScale).observe(viewport);
    } else {
        window.addEventListener('resize', applyScale);
    }

    $('gridToggle').addEventListener('change', function (event) {
        canvasGrid.classList.toggle('is-off', !event.target.checked);
    });

    $('snapToggle').addEventListener('change', function (event) {
        snapEnabled = event.target.checked;
    });

    /* ------------------------------------------------------------
       Background
    ------------------------------------------------------------ */

    function setBackgroundStatus(message, isError = false) {
        backgroundStatus.replaceChildren();
        backgroundStatus.classList.toggle('is-error', isError);

        const icon = document.createElement('i');
        icon.className = isError ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-info';
        icon.setAttribute('aria-hidden', 'true');

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
        canvas.style.backgroundColor = '#ffffff';

        if (currentBackgroundUrl) {
            canvas.style.backgroundImage = 'url("' + currentBackgroundUrl.replace(/["\\]/g, '\\$&') + '")';
            canvas.style.backgroundSize = '100% 100%';
            canvas.style.backgroundPosition = 'center';
            canvas.style.backgroundRepeat = 'no-repeat';

            setBackgroundStatus(
                backgroundInput.files.length
                    ? 'New background selected. Save the design to upload it.'
                    : 'Saved background loaded.'
            );
        } else {
            canvas.style.backgroundImage = 'none';

            setBackgroundStatus(
                removeSavedBackground
                    ? 'Background will be removed when you save.'
                    : 'No background loaded.'
            );
        }
    }

    function acceptImage(file) {
        if (!file) return;

        if (!ALLOWED_IMAGES.includes(file.type)) {
            backgroundInput.value = '';
            setBackgroundStatus('Please choose a JPG, JPEG or PNG image.', true);
            return;
        }

        if (file.size > MAX_IMAGE_SIZE) {
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
        markDirty();
    }

    $('chooseImageBtn').addEventListener('click', function () {
        backgroundInput.click();
    });

    backgroundInput.addEventListener('change', function () {
        acceptImage(backgroundInput.files[0]);
    });

    $('removeBackgroundBtn').addEventListener('click', function () {
        if (!currentBackgroundUrl && !savedBackgroundPath && !backgroundInput.files.length) {
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
        markDirty();
    });

    $('restoreBackgroundBtn').addEventListener('click', function () {
        revokeTemporaryUrl();

        backgroundInput.value = '';
        fileName.textContent = 'No new image selected';

        removeSavedBackground = false;
        removeBackgroundInput.value = '0';

        currentBackgroundPath = savedBackgroundPath;
        currentBackgroundUrl = savedBackgroundUrl;

        updateCanvasBackground();
    });

    function handleDrop(event) {
        event.preventDefault();
        dropZone.classList.remove('drag-over');

        const file = event.dataTransfer && event.dataTransfer.files[0];
        if (!file) return;

        if (!ALLOWED_IMAGES.includes(file.type)) {
            setBackgroundStatus('Please drop a JPG, JPEG or PNG image.', true);
            return;
        }

        if (file.size > MAX_IMAGE_SIZE) {
            setBackgroundStatus('The image must be 5 MB or smaller.', true);
            return;
        }

        const transfer = new DataTransfer();
        transfer.items.add(file);
        backgroundInput.files = transfer.files;

        acceptImage(file);
    }

    function handleDragOver(event) {
        event.preventDefault();
        dropZone.classList.add('drag-over');
    }

    [dropZone, viewport].forEach(target => {
        target.addEventListener('dragover', handleDragOver);
        target.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
        target.addEventListener('drop', handleDrop);
    });

    /* ------------------------------------------------------------
       Elements
    ------------------------------------------------------------ */

    function defaults(type, offset = 0) {
        const slot = SLOTS[type];

        return {
            id: newId(),
            type,
            x: slot.x + offset * 30,
            y: slot.y + offset * 30,
            width: slot.w,
            height: slot.h,
            fontSize: slot.fs,
            color: '#222222',
            rotation: 0
        };
    }

    function normalizeElement(element) {
        const fallback = defaults(element.type);

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

    function clampElement(element) {
        element.width = Math.max(1, Math.min(W, Math.round(Number(element.width) || 1)));
        element.height = Math.max(1, Math.min(H, Math.round(Number(element.height) || 1)));
        element.x = Math.max(0, Math.min(W - element.width, Math.round(Number(element.x) || 0)));
        element.y = Math.max(0, Math.min(H - element.height, Math.round(Number(element.y) || 0)));
        element.fontSize = Math.max(6, Math.min(120, Math.round(Number(element.fontSize) || 24)));
        element.rotation = Math.max(-360, Math.min(360, Math.round(Number(element.rotation) || 0)));
    }

    function elementLabel(element) {
        const sameType = elements.filter(item => item.type === element.type);
        const label = TYPES[element.type].label;

        return sameType.length > 1
            ? label + ' ' + (sameType.indexOf(element) + 1)
            : label;
    }

    function buildNode(element) {
        const node = document.createElement('div');
        node.dataset.id = element.id;

        const label = document.createElement('span');
        label.className = 'element-label';
        node.appendChild(label);

        ['nw', 'ne', 'sw', 'se'].forEach(position => {
            const handle = document.createElement('span');
            handle.className = 'resize-handle ' + position;
            handle.dataset.handle = position;
            handle.addEventListener('pointerdown', startResize);
            node.appendChild(handle);
        });

        node.addEventListener('pointerdown', startDrag);
        node.addEventListener('click', event => event.stopPropagation());

        return node;
    }

    function syncNode(element) {
        const node = nodeFor(element.id);
        if (!node) return;

        node.className = 'ticket-element ' + element.type +
            (element.id === selectedId ? ' selected' : '') +
            (element.rotation !== 0 ? ' rotated' : '');

        node.style.left = element.x + 'px';
        node.style.top = element.y + 'px';
        node.style.width = element.width + 'px';
        node.style.height = element.height + 'px';
        node.style.fontSize = element.fontSize + 'px';
        node.style.color = element.color;
        node.style.transform = 'rotate(' + element.rotation + 'deg)';

        node.querySelector('.element-label').textContent = TYPES[element.type].sample;
    }

    function render() {
        canvas.querySelectorAll('.ticket-element').forEach(node => node.remove());

        emptyMessage.style.display = elements.length ? 'none' : 'flex';

        elements.forEach(element => {
            canvas.appendChild(buildNode(element));
            syncNode(element);
        });

        renderLayers();
    }

    function select(id) {
        selectedId = id;
        elements.forEach(syncNode);
        renderLayers();
        renderInspector();
    }

    function renderLayers() {
        layerList.replaceChildren();
        layersWrap.hidden = elements.length === 0;

        elements.forEach(element => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'ts-layer' + (element.id === selectedId ? ' is-selected' : '');
            button.setAttribute('aria-pressed', element.id === selectedId ? 'true' : 'false');

            const icon = document.createElement('i');
            icon.className = 'fa-solid ' + TYPES[element.type].icon;
            icon.setAttribute('aria-hidden', 'true');

            const text = document.createElement('span');
            text.textContent = elementLabel(element);

            button.append(icon, text);
            button.addEventListener('click', () => select(element.id));

            layerList.appendChild(button);
        });
    }

    function addElement(type) {
        const offset = elements.filter(item => item.type === type).length;
        const element = defaults(type, offset);

        elements.push(element);
        markDirty();
        render();
        select(element.id);
    }

    function deleteSelected() {
        if (!selectedId) return;

        elements = elements.filter(element => element.id !== selectedId);
        selectedId = null;

        markDirty();
        render();
        renderInspector();
    }

    function duplicateSelected() {
        const source = selectedElement();
        if (!source) return;

        const copy = { ...source, id: newId(), x: source.x + 20, y: source.y + 20 };
        clampElement(copy);

        elements.push(copy);
        markDirty();
        render();
        select(copy.id);
    }

    document.querySelectorAll('.add-element').forEach(button => {
        button.addEventListener('click', () => addElement(button.dataset.type));
    });

    $('quickStartBtn').addEventListener('click', function (event) {
        event.stopPropagation();

        ['qr', 'attendee_name', 'ticket_number', 'event_name'].forEach(type => {
            elements.push(defaults(type));
        });

        markDirty();
        render();
        select(null);
    });

    deleteButton.addEventListener('click', deleteSelected);
    duplicateButton.addEventListener('click', duplicateSelected);

    canvas.addEventListener('click', function (event) {
        if (event.target === canvas || event.target === canvasGrid || emptyMessage.contains(event.target)) {
            select(null);
        }
    });

    /* ------------------------------------------------------------
       Dragging and resizing
    ------------------------------------------------------------ */

    function startDrag(event) {
        if (event.button !== 0) return;
        if (event.target.closest('.resize-handle')) return;

        const element = byId(event.currentTarget.dataset.id);
        if (!element) return;

        if (selectedId !== element.id) {
            select(element.id);
        }

        drag = {
            mode: 'move',
            id: element.id,
            startX: event.clientX,
            startY: event.clientY,
            ox: element.x,
            oy: element.y,
            moved: false
        };

        event.preventDefault();
    }

    function startResize(event) {
        if (event.button !== 0) return;

        const node = event.currentTarget.closest('.ticket-element');
        const element = node ? byId(node.dataset.id) : null;
        if (!element) return;

        drag = {
            mode: 'resize',
            handle: event.currentTarget.dataset.handle,
            id: element.id,
            startX: event.clientX,
            startY: event.clientY,
            ox: element.x,
            oy: element.y,
            ow: element.width,
            oh: element.height,
            moved: false
        };

        event.preventDefault();
        event.stopPropagation();
    }

    document.addEventListener('pointermove', function (event) {
        if (!drag) return;

        const element = byId(drag.id);
        if (!element) return;

        const scale = currentScale || 1;
        const dx = (event.clientX - drag.startX) / scale;
        const dy = (event.clientY - drag.startY) / scale;

        if (drag.mode === 'move') {
            let nx = drag.ox + dx;
            let ny = drag.oy + dy;

            if (snapEnabled) {
                nx = Math.round(nx / GRID) * GRID;
                ny = Math.round(ny / GRID) * GRID;
            }

            element.x = nx;
            element.y = ny;
        } else {
            const handle = drag.handle;
            let x = drag.ox, y = drag.oy, w = drag.ow, h = drag.oh;

            if (handle.includes('e')) w = drag.ow + dx;
            if (handle.includes('s')) h = drag.oh + dy;
            if (handle.includes('w')) { w = drag.ow - dx; x = drag.ox + dx; }
            if (handle.includes('n')) { h = drag.oh - dy; y = drag.oy + dy; }

            if (element.type === 'qr') {
                const size = Math.min(H, Math.max(w, h));
                if (handle.includes('w')) x = drag.ox + drag.ow - size;
                if (handle.includes('n')) y = drag.oy + drag.oh - size;
                w = size;
                h = size;
            }

            const MIN = 20;
            if (w < MIN) { if (handle.includes('w')) x -= (MIN - w); w = MIN; }
            if (h < MIN) { if (handle.includes('n')) y -= (MIN - h); h = MIN; }

            element.x = x;
            element.y = y;
            element.width = w;
            element.height = h;
        }

        clampElement(element);
        syncNode(element);
        syncInspectorValues();

        if (Math.abs(dx) > 0 || Math.abs(dy) > 0) drag.moved = true;
    });

    function endDrag() {
        if (drag && drag.moved) markDirty();
        drag = null;
    }

    document.addEventListener('pointerup', endDrag);
    document.addEventListener('pointercancel', endDrag);

    /* ------------------------------------------------------------
       Keyboard
    ------------------------------------------------------------ */

    document.addEventListener('keydown', function (event) {
        const target = event.target;

        if (target && (/^(INPUT|SELECT|TEXTAREA)$/.test(target.tagName) || target.isContentEditable)) return;
        if (target !== document.body && !stage.contains(target)) return;

        const element = selectedElement();
        if (!element) return;

        const step = event.shiftKey ? 10 : 1;
        let moved = true;

        switch (event.key) {
            case 'ArrowLeft':  element.x -= step; break;
            case 'ArrowRight': element.x += step; break;
            case 'ArrowUp':    element.y -= step; break;
            case 'ArrowDown':  element.y += step; break;
            case 'Delete':
            case 'Backspace':
                event.preventDefault();
                deleteSelected();
                return;
            case 'Escape':
                select(null);
                return;
            default:
                moved = false;
        }

        if (moved) {
            event.preventDefault();
            clampElement(element);
            syncNode(element);
            syncInspectorValues();
            markDirty();
        }
    });

    /* ------------------------------------------------------------
       Inspector
    ------------------------------------------------------------ */

    function applyProp(element, prop, value) {
        if (element.type === 'qr' && (prop === 'width' || prop === 'height')) {
            const size = Math.max(1, Math.min(H, Math.round(value)));
            element.width = size;
            element.height = size;
        } else {
            element[prop] = value;
        }

        clampElement(element);
        syncNode(element);
        syncInspectorValues();
        markDirty();
    }

    function syncInspectorValues() {
        const element = selectedElement();
        if (!element) return;

        ['x', 'y', 'width', 'height', 'fontSize', 'rotation'].forEach(prop => {
            const input = refs[prop];
            if (input && document.activeElement !== input) input.value = element[prop];
        });

        if (refs.rotationSlider) refs.rotationSlider.value = element.rotation;
        if (refs.color && document.activeElement !== refs.color) refs.color.value = toHex(element.color);
    }

    function addSection(title) {
        const section = document.createElement('div');
        section.className = 'ts-section';

        const heading = document.createElement('h3');
        heading.textContent = title;
        section.appendChild(heading);

        propertiesPanel.appendChild(section);
        return section;
    }

    function numberField(label, prop, min, max) {
        const wrapper = document.createElement('div');
        wrapper.className = 'ts-field';

        const id = 'prop-' + prop;

        const labelNode = document.createElement('label');
        labelNode.htmlFor = id;
        labelNode.textContent = label;

        const input = document.createElement('input');
        input.id = id;
        input.type = 'number';
        input.className = 'form-control';
        input.min = min;
        input.max = max;
        input.step = 1;
        input.value = selectedElement()[prop];

        input.addEventListener('input', function () {
            const element = selectedElement();
            if (!element || input.value === '') return;

            const number = Number(input.value);
            if (!Number.isFinite(number)) return;

            applyProp(element, prop, number);
        });

        refs[prop] = input;
        wrapper.append(labelNode, input);
        return wrapper;
    }

    function row(...children) {
        const wrapper = document.createElement('div');
        wrapper.className = 'ts-row';
        wrapper.append(...children);
        return wrapper;
    }

    function renderInspector() {
        const element = selectedElement();

        propertiesPanel.replaceChildren();
        refs = {};

        inspectorEmpty.hidden = !!element;
        inspectorBody.hidden = !element;
        deleteButton.disabled = !element;

        if (!element) return;

        const meta = TYPES[element.type];

        inspectorType.replaceChildren();
        const icon = document.createElement('i');
        icon.className = 'fa-solid ' + meta.icon;
        icon.setAttribute('aria-hidden', 'true');
        inspectorType.append(icon, document.createTextNode(elementLabel(element)));

        // Position
        const position = addSection('Position');
        position.appendChild(row(
            numberField('Left (X)', 'x', 0, W - 1),
            numberField('Top (Y)', 'y', 0, H - 1)
        ));

        const centerRow = document.createElement('div');
        centerRow.className = 'ts-center-row';

        const centerH = document.createElement('button');
        centerH.type = 'button';
        centerH.className = 'ts-btn ts-btn-ghost ts-btn-sm';
        centerH.innerHTML = '<i class="fa-solid fa-arrows-left-right" aria-hidden="true"></i> Center across';
        centerH.addEventListener('click', function () {
            const current = selectedElement();
            if (current) applyProp(current, 'x', Math.round((W - current.width) / 2));
        });

        const centerV = document.createElement('button');
        centerV.type = 'button';
        centerV.className = 'ts-btn ts-btn-ghost ts-btn-sm';
        centerV.innerHTML = '<i class="fa-solid fa-arrows-up-down" aria-hidden="true"></i> Center down';
        centerV.addEventListener('click', function () {
            const current = selectedElement();
            if (current) applyProp(current, 'y', Math.round((H - current.height) / 2));
        });

        centerRow.append(centerH, centerV);
        position.appendChild(centerRow);

        // Size
        const size = addSection('Size');
        size.appendChild(row(
            numberField('Width', 'width', 1, W),
            numberField('Height', 'height', 1, H)
        ));

        if (element.type === 'qr') {
            const note = document.createElement('p');
            note.className = 'ts-note';
            note.textContent = 'The QR code stays square so it scans reliably.';
            size.appendChild(note);
        }

        // Text
        if (element.type !== 'qr') {
            const text = addSection('Text');
            text.appendChild(numberField('Font size (px)', 'fontSize', 6, 120));

            const colorWrap = document.createElement('div');
            colorWrap.className = 'ts-field';

            const colorLabel = document.createElement('label');
            colorLabel.htmlFor = 'prop-color';
            colorLabel.textContent = 'Color';

            const colors = document.createElement('div');
            colors.className = 'ts-colors';

            const colorInput = document.createElement('input');
            colorInput.type = 'color';
            colorInput.id = 'prop-color';
            colorInput.value = toHex(element.color);
            colorInput.addEventListener('input', function () {
                const current = selectedElement();
                if (current) applyProp(current, 'color', colorInput.value);
            });
            refs.color = colorInput;

            SWATCHES.forEach(hex => {
                const swatch = document.createElement('button');
                swatch.type = 'button';
                swatch.className = 'ts-swatch';
                swatch.style.background = hex;
                swatch.setAttribute('aria-label', 'Use color ' + hex);
                swatch.addEventListener('click', function () {
                    const current = selectedElement();
                    if (!current) return;
                    applyProp(current, 'color', hex);
                    colorInput.value = hex;
                });
                colors.appendChild(swatch);
            });

            colors.appendChild(colorInput);
            colorWrap.append(colorLabel, colors);
            text.appendChild(colorWrap);
        }

        // Rotation
        const rotation = addSection('Rotation');
        rotation.appendChild(numberField('Angle (degrees)', 'rotation', -360, 360));

        const slider = document.createElement('input');
        slider.type = 'range';
        slider.min = -180;
        slider.max = 180;
        slider.step = 1;
        slider.value = element.rotation;
        slider.setAttribute('aria-label', 'Rotation slider');
        slider.addEventListener('input', function () {
            const current = selectedElement();
            if (current) applyProp(current, 'rotation', Number(slider.value));
        });
        refs.rotationSlider = slider;

        const sliderWrap = document.createElement('div');
        sliderWrap.className = 'ts-field';
        sliderWrap.appendChild(slider);
        rotation.appendChild(sliderWrap);

        if (element.rotation !== 0) {
            const note = document.createElement('p');
            note.className = 'ts-note';
            note.textContent = 'Corner handles are hidden while rotated. Use the size fields to resize.';
            rotation.appendChild(note);
        }
    }

    /* ------------------------------------------------------------
       Load, reset and category switching
    ------------------------------------------------------------ */

    function loadSelectedCategory() {
        const data = categoryData[categorySelect.value] || {};
        const saved = data.design || {};

        elements = Array.isArray(saved.elements)
            ? saved.elements.filter(element => element && TYPES[element.type]).map(normalizeElement)
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
        drag = null;

        updateCanvasBackground();
        applyScale();
        render();
        renderInspector();
        markClean();
    }

    function confirmDiscard(message) {
        return !dirty || confirm(message);
    }

    $('loadDesignBtn').addEventListener('click', function () {
        if (confirmDiscard('Reload the saved design? Your unsaved changes will be lost.')) {
            loadSelectedCategory();
        }
    });

    $('resetDesignBtn').addEventListener('click', function () {
        if (!elements.length) return;

        if (!confirm('Clear every element from the canvas? The saved design is not changed until you save.')) {
            return;
        }

        elements = [];
        selectedId = null;

        markDirty();
        render();
        renderInspector();
    });

    let previousCategoryId = categorySelect.value;

    categorySelect.addEventListener('change', function () {
        if (!confirmDiscard('Switch category? Your unsaved changes will be lost.')) {
            categorySelect.value = previousCategoryId;
            return;
        }

        previousCategoryId = categorySelect.value;
        loadSelectedCategory();
    });

    /* ------------------------------------------------------------
       Save
    ------------------------------------------------------------ */

    form.addEventListener('submit', function (event) {
        if (!elements.length) {
            event.preventDefault();
            alert('Add at least one element before saving the design.');
            return;
        }

        if (backgroundInput.files.length && !ALLOWED_IMAGES.includes(backgroundInput.files[0].type)) {
            event.preventDefault();
            alert('Please choose a JPG, JPEG or PNG background.');
            return;
        }

        hiddenInput.value = JSON.stringify({
            version: 1,
            canvas: {
                width: W,
                height: H
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

        submitting = true;
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Saving...';
    });

    window.addEventListener('beforeunload', function (event) {
        if (dirty && !submitting) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    loadSelectedCategory();
});
</script>
@endif
@endsection