<template>
<div>
  <!-- <q-item>
    <q-item-section v-if="icon" avatar>
      <q-icon :name="icon" />
    </q-item-section>
</q-item> -->
    <q-item-section>
      <!-- <q-item-label>{{ title }}</q-item-label>
      <q-item-label caption>
        {{ caption }}
      </q-item-label> -->
      <q-expansion-item
          expand-separator
          label="Medicamento"
          >
          <q-item to="/medicine">
            <q-item-section avatar>
              <q-icon name="fas fa-clipboard-list" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Medicamentos</q-item-label>
            </q-item-section>
          </q-item>
        </q-expansion-item>
    </q-item-section>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "EssentialLink",
  /* props: {
    title: {
      type: String,
      required: true,
    },

    caption: {
      type: String,
      default: "",
    },

    link: {
      type: String,
      default: "#",
    },

    icon: {
      type: String,
      default: "",
    },
  }, */
});
</script>
