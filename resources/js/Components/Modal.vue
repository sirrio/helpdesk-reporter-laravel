<template>
  <div class="modal-overlay">
    <div class="modal-content">
      <header class="modal-header">
        <slot name="header">
          Modal Header
        </slot>
      </header>
      <main class="modal-body">
        <slot name="body">
          Modal Body
        </slot>
      </main>
      <footer class="modal-footer">
        <slot name="footer">
          Modal Footer
        </slot>
      </footer>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  max-width: 500px;
  width: 100%;
  max-height: 90%;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
}

.modal-close-button {
  cursor: pointer;
  background: transparent;
  border: 0;
  outline: 0;
  font-size: 1.5rem;
  color: #333;
}

.modal-body {
  padding: 1rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 1rem;
  border-top: 1px solid #e9ecef;
}
</style>
