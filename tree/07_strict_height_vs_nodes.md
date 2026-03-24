# 📏 Strict Binary Tree: Height vs. Nodes

In a **Strict Binary Tree**, the requirement that every node must have either **0 or 2 children** changes the mathematical relationship between height and nodes significantly.

---

## 🏗️ Scenario 1: If Height (h) is Given
If we know the height, what are the minimum and maximum nodes possible?

### 1. Minimum Nodes ($n_{min}$)
The "thinnest" strict tree is no longer a simple line (skewed). To stay strict, every internal node must have exactly 2 children.
- **Formula:** $n = 2h + 1$
- **Example ($h=3$):** Nodes = $2(3) + 1 = 7$.

### 2. Maximum Nodes ($n_{max}$)
The densest strict tree is a **Perfect Binary Tree**.
- **Formula:** $n = 2^{h+1} - 1$
- **Example ($h=3$):** Nodes = $2^4 - 1 = 15$.

---

## 📸 Visual Comparison (h = 3)

### ✅ Min Nodes (n=7)
Each level adds exactly one internal node and one leaf to keep it strict.
```mermaid
graph TD
    A1(( )) --- B1(( ))
    A1 --- C1(( ))
    B1 --- D1(( ))
    B1 --- E1(( ))
    D1 --- F1(( ))
    D1 --- G1(( ))
```

### ✅ Max Nodes (n=15)
A Perfect Binary Tree where every level is full.
```mermaid
graph TD
    R(( )) --- L1(( )) --- L2(( )) --- L3(( ))
    L2 --- L4(( ))
    L1 --- L5(( )) --- L6(( ))
    L5 --- L7(( ))
    R --- R1(( )) --- R2(( )) --- R3(( ))
    R2 --- R4(( ))
    R1 --- R5(( )) --- R6(( ))
    R5 --- R7(( ))
```

---

## 📐 Scenario 2: If Nodes (n) are Given
If we know the count of nodes, what height range is possible?

### 1. Minimum Height ($h_{min}$)
Same as a general binary tree (happens when the tree is full/perfect).
- **Formula:** $h = \log_2(n+1) - 1$

### 2. Maximum Height ($h_{max}$)
Since each level must have at least 2 nodes (to stay strict), the tree cannot be as tall as a simple skewed tree.
- **Formula:** $h = \frac{n-1}{2}$
- **Derivation**: From $n = 2h + 1 \Rightarrow n - 1 = 2h \Rightarrow h = \frac{n-1}{2}$.

---

## 📊 Summary Comparison
| Feature | General Binary Tree | Strict Binary Tree |
| :--- | :--- | :--- |
| **Min Nodes** | $h + 1$ | **$2h + 1$** |
| **Max Nodes** | $2^{h+1} - 1$ | $2^{h+1} - 1$ |
| **Min Height** | $\log_2(n+1) - 1$ | $\log_2(n+1) - 1$ |
| **Max Height** | $n - 1$ | **$\frac{n-1}{2}$** |
