# 🌳 Strict Binary Tree: Internal vs. External Nodes

In a **Strict Binary Tree**, the relationship between internal nodes ($i$) and external nodes ($e$) is one of the most elegant mathematical properties in tree data structures.

---

## 📝 Definitions
- **Internal Nodes ($i$)**: Nodes that have children (in a strict tree, they **must** have 2 children).
- **External Nodes ($e$)**: Nodes with 0 children, also known as **Leaves**.

---

## 📐 The Golden Rule: $e = i + 1$
In any strict binary tree, the number of external nodes is always **exactly one more** than the number of internal nodes.

> [!TIP]
> This is a specialized version of the general formula $n_0 = n_2 + 1$. Because in a strict tree, every internal node has 2 children ($i = n_2$), the formula simplifies directly to $e = i + 1$.

---

## 📸 Whiteboard Proofs

### CASE 1: $i = 3, e = 4$
A small perfect-style tree.
```mermaid
graph TD
    A1((i1)) --- B1((i2))
    A1 --- C1((i3))
    B1 --- D1((e1))
    B1 --- E1((e2))
    C1 --- F1((e3))
    C1 --- G1((e4))
```
**Check:** $4 = 3 + 1$ ✅

### CASE 2: $i = 5, e = 6$
A deeper, non-symmetrical strict tree exactly as shown on the whiteboard.
```mermaid
graph TD
    A2((i1)) --- B2((i2))
    A2 --- C2((i3))
    B2 --- D2((e1))
    B2 --- E2((e2))
    C2 --- F2((e3))
    C2 --- G2((i4))
    G2 --- H2((e4))
    G2 --- I2((i5))
    I2 --- J2((e5))
    I2 --- K2((e6))
```
**Counts:**
- **Internal ($i$):** A2, B2, C2, G2, I2 (**Total = 5**)
- **External ($e$):** D2, E2, F2, H2, J2, K2 (**Total = 6**)
**Check:** $6 = 5 + 1$ ✅

---

## 💡 Summary Card
| Node Type | Count | Relationship |
| :--- | :--- | :--- |
| **Internal** | **$i$** | $i = e - 1$ |
| **External** | **$e$** | **$e = i + 1$** |
| **Total Nodes** | **$n$** | $n = i + e = 2i + 1$ |
