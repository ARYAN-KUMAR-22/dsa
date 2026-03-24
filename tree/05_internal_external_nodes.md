# 🌳 Internal and External Nodes (Degree Relationship)

In a binary tree, nodes are often categorized by how many children they have. This is called their **Degree**. Understanding this relationship is crucial for solving many DSA problems.

---

## 📝 Key Definitions
- **External Nodes (Leaves)**: Nodes with **0 children**. We denote their count as **$n_0$** or **$deg(0)$**.
- **Internal Nodes**: Nodes with **at least one child**. This includes:
    - Nodes with **1 child** (**$n_1$** or **$deg(1)$**).
    - Nodes with **2 children** (**$n_2$** or **$deg(2)$**).

---

## 📊 The Golden Rule: $n_0 = n_2 + 1$
In any binary tree, the number of leaf nodes (**$n_0$**) is always **one more** than the number of nodes with two children (**$n_2$**).

**Wait, what about nodes with 1 child ($n_1$)?**
The number of leaves **does not depend on $n_1$**. No matter how many nodes with 1 child you add, the relationship between $n_0$ and $n_2$ remains the same!

---

## 📸 Whiteboard Examples (Step-by-Step)
Let's recreate the three cases from the whiteboard to prove the formula.

### CASE 1: Standard Tree
- **Logic**: 2 forks ($n_2$), 2 straight paths ($n_1$), and 3 leaves ($n_0$).
```mermaid
graph TD
    A1(( )) --- B1(( ))
    A1 --- C1(( ))
    B1 --- D1(( ))
    B1 --- E1(( ))
    C1 --- F1(( )) --- G1(( ))
```
**Counts:**
- **$deg(2) = 2$** (Nodes A1, B1)
- **$deg(1) = 2$** (Nodes C1, F1)
- **$deg(0) = 3$** (Nodes D1, E1, G1)
**Check:** $n_0 = n_2 + 1 \Rightarrow 3 = 2 + 1$ ✅

### CASE 2: Complex Tree
- **Logic**: 3 forks ($n_2$), 5 straight paths ($n_1$), and 4 leaves ($n_0$).
```mermaid
graph TD
    A2(( )) --- B2(( ))
    A2 --- C2(( ))
    B2 --- D2(( ))
    B2 --- E2(( ))
    C2 --- F2(( ))
    C2 --- G2(( ))
    D2 --- H2(( )) --- I2(( )) --- J2(( )) --- K2(( )) --- L2(( ))
```
**Counts:**
- **$deg(2) = 3$** (Nodes A2, B2, C2)
- **$deg(1) = 5$** (Nodes D2, H2, I2, J2, K2)
- **$deg(0) = 4$** (Nodes E2, F2, G2, L2)
**Check:** $n_0 = n_2 + 1 \Rightarrow 4 = 3 + 1$ ✅

### CASE 3: Skewed Combination
- **Logic**: 1 fork ($n_2$), 4 straight paths ($n_1$), and 2 leaves ($n_0$).
```mermaid
graph TD
    A3(( )) --- B3(( )) --- D3(( )) --- E3(( )) --- F3(( )) --- G3(( ))
    A3 --- C3(( ))
```
**Counts:**
- **$deg(2) = 1$** (Node A3)
- **$deg(1) = 4$** (Nodes B3, D3, E3, F3)
- **$deg(0) = 2$** (Nodes G3, C3)
**Check:** $n_0 = n_2 + 1 \Rightarrow 2 = 1 + 1$ ✅

---

## 💡 Why does this work? (The Intuition)
Think of a node with **2 children** as a "fork" in the road.
- Every time you add a fork ($n_2$), you create an extra branch that must eventually end in a leaf ($n_0$).
- A node with **1 child** ($n_1$) is just a "straight path" and doesn't create new branches, so it doesn't change the leaf count.

---

## 📏 Master Formula Summary
| Type | Notation | Description |
| :--- | :--- | :--- |
| **Leaves** | **$n_0$** | Nodes with 0 children. |
| **Two-Child Nodes** | **$n_2$** | Nodes with 2 children. |
| **Relationship** | **$n_0 = n_2 + 1$** | **Leaves = (Two-Child Nodes) + 1** |
