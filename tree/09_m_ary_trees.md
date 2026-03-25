# 🌳 m-ary Trees (Generalizing Binary Trees)

An **m-ary Tree** (or n-ary tree) is a tree where every node can have **at most m** children. 

---

## 🏛️ 1. General m-ary Tree
In a general m-ary tree, the number of children for any node belongs to the set:
**$$\text{Children} \in \{0, 1, 2, \dots, m\}$$**

### 📸 Visual Example (4-ary Tree: m=4)

**✅ VALID 4-ary Tree**
Every node has between 0 and 4 children.
```mermaid
graph TD
    A1(( )) --- B1(( ))
    A1 --- C1(( ))
    B1 --- D1(( ))
    B1 --- E1(( ))
    B1 --- F1(( ))
    B1 --- G1(( ))
    C1 --- H1(( ))
```

**❌ INVALID 4-ary Tree**
The node marked with **X** has **5 children**, which exceeds $m=4$.
```mermaid
graph TD
    A2((X)) --- B2(( ))
    A2 --- C2(( ))
    A2 --- D2(( ))
    A2 --- E2(( ))
    A2 --- F2(( ))
    A2 --- G2(( ))
```

---

## 🛡️ 2. Strict m-ary Tree
A Strict m-ary tree follows a much tighter rule. The number of children for any node belongs to the set:
**$$\text{Children} \in \{0, m\}$$**

### 📸 Visual Example (Strict 3-ary Tree: m=3)

**✅ VALID Strict 3-ary**
Every node has exactly 0 or 3 children.
```mermaid
graph TD
    A3(( )) --- B3(( ))
    A3 --- C3(( ))
    A3 --- D3(( ))
    B3 --- E3(( ))
    B3 --- F3(( ))
    B3 --- G3(( ))
```

**❌ INVALID Strict 3-ary**
The node marked with **X** has only **2 children**, which is not allowed.
```mermaid
graph TD
    A4(( )) --- B4(( ))
    A4 --- C4(( ))
    A4 --- D4(( ))
    D4 --- E4((X))
    E4 --- F4(( ))
    E4 --- G4(( ))
```

---

## 💾 3. Memory Representation
In a programming language like C/C++ or Java, an m-ary tree node is usually represented using an **array of pointers**.

**Node Structure:**
- **Data Field**: Stores the actual value.
- **Child Array**: An array of size $m$ to store pointers to children.

```cpp
struct Node {
    int data;
    Node* children[M]; // Array of M pointers
};
```

---

## 📐 4. Height vs. Nodes (Strict m-ary)

### 1. Minimum Nodes ($n_{min}$)
- **Formula:** $n = m \times h + 1$
- **Example ($m=3, h=2$):** $3(2) + 1 = 7$.

### 2. Maximum Nodes ($n_{max}$)
- **Formula:** $n = \frac{m^{h+1}-1}{m-1}$
- **Example ($m=3, h=2$):** $\frac{3^3 - 1}{3-1} = 13$.

---

## 📐 5. Scenario 2: If Nodes (n) are Given
If we know the total number of nodes, what height range is possible?

### 1. Minimum Height ($h_{min}$)
Happens when the tree is packed "maximally" (Perfect m-ary tree).
- **Formula:** $h = \log_m [n(m-1) + 1] - 1$
- **Example ($n=13, m=3$):** $h = \log_3[13(2)+1]-1 = \log_3(27)-1 = 3-1 = 2$.

### 2. Maximum Height ($h_{max}$)
Happens when the tree is "thinnest" (Strict zig-zag m-ary tree).
- **Formula:** $h = \frac{n-1}{m}$
- **Example ($n=7, m=3$):** $h = \frac{7-1}{3} = \frac{6}{3} = 2$.

---

## ⚖️ 6. Internal vs. External Nodes (Strict)
In a strict m-ary tree, the number of leaves ($e$) and internal nodes ($i$) satisfy:

**$$e = (m-1)i + 1$$**

---

## 📊 Summary Card
| Property | Formula |
| :--- | :--- |
| **Max Nodes (fixed h)** | $n = \frac{m^{h+1}-1}{m-1}$ |
| **Min Nodes (fixed h)** | $n = m \cdot h + 1$ |
| **Min Height (fixed n)** | $h = \log_m[n(m-1)+1] - 1$ |
| **Max Height (fixed n)** | $h = \frac{n-1}{m}$ |
| **Leaves (fixed i)** | $e = (m-1)i + 1$ |
| **Internal Nodes (fixed n)** | $i = \frac{n-1}{m}$ |
