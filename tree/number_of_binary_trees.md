# 🔢 How many Binary Trees can you make?

When we have **n nodes**, how many different binary trees can we build? The answer depends on one big question: **Are the nodes labeled or unlabeled?**

---

## 🏗️ Concept 1: Unlabeled Nodes (Shapes Only)
If all nodes are identical (just empty circles), we only care about the **shape** of the tree.

**The Formula:**
We use the **Catalan Number** to find the number of unique shapes:
$$T(n) = \frac{1}{n+1} \binom{2n}{n}$$

### 🗃️ Card: Shapes for n=3
For $n=3$ nodes, there are exactly **5** possible shapes:
```mermaid
graph TD
    subgraph Shape1
        1(( )) --- 2(( )) --- 3(( ))
    end
    subgraph Shape2
        4(( )) --- 5(( ))
        5 --- 6(( ))
    end
    subgraph Shape3
        7(( )) --- 8(( ))
        7 --- 9(( ))
    end
    subgraph Shape4
        10(( )) --- 11(( ))
        11 --- 12(( ))
    end
    subgraph Shape5
        13(( )) --- 14(( )) --- 15(( ))
    end
```
**Quick Results Table:**
| Nodes (n) | Number of Shapes $T(n)$ |
| :--- | :--- |
| **0** | **1** (Empty Tree) |
| **1** | 1 |
| **2** | 2 |
| **3** | **5** |
| **4** | **14** |
| **5** | **42** |
| **6** | **132** |

---

## 🔄 Concept 2: The Recursive Formula
Another way to find $T(n)$ is to build it using results from smaller trees. This is a very "pro" way to think about it!

**The Summation Formula:**
$$T(n) = \sum_{i=1}^{n} T(i-1) \times T(n-i)$$

**How it works (for n=3):**
To build a tree with 3 nodes, we pick 1 node as the **root**. That leaves 2 nodes to be split between the Left and Right subtrees:
- **Case 1**: 0 nodes left, 2 nodes right $\rightarrow T(0) \times T(2) = 1 \times 2 = 2$
- **Case 2**: 1 node left, 1 node right $\rightarrow T(1) \times T(1) = 1 \times 1 = 1$
- **Case 3**: 2 nodes left, 0 nodes right $\rightarrow T(2) \times T(0) = 2 \times 1 = 2$
- **Total**: $2 + 1 + 2 = \mathbf{5}$

---

## 📝 Concept 3: Shapes vs. Filling (Labeled Nodes)
When nodes are **labeled** (e.g., A, B, C), we multiply the number of shapes by the number of ways to "fill" them.

**The Formula:**
$$\text{Total Trees} = \underbrace{\frac{\binom{2n}{n}}{n+1}}_{\text{Shapes}} \times \underbrace{n!}_{\text{Filling}} = \frac{(2n)!}{(n+1)!}$$

> [!TIP]
> **Shapes** = Catalan Number (Unlabeled)
> **Filling** = Factorial (Labeled)

---

## 🧮 Advanced Calculation: T(6)
Using the Catalan formula:
$$T(6) = \frac{\binom{12}{6}}{7} = \frac{924}{7} = \mathbf{132}$$

Using the Recursive formula:
$$T(6) = (T_0 T_5) + (T_1 T_4) + (T_2 T_3) + (T_3 T_2) + (T_4 T_1) + (T_5 T_0)$$
$$T(6) = (1 \times 42) + (1 \times 14) + (2 \times 5) + (5 \times 2) + (14 \times 1) + (42 \times 1)$$
$$T(6) = 42 + 14 + 10 + 10 + 14 + 42 = \mathbf{132}$$

---

## 📏 Concept 3: Maximum Height (Skewed Trees)
If we want to know how many binary trees can be made with the **maximum possible height** (only 1 node per level), we use a simpler formula.

**The Formula:**
$$\text{Max Height Trees} = 2^{n-1}$$

**Example for n=3:**
- $2^{3-1} = 2^2 = 4$
- These are the 4 skewed shapes (Left-Left, Left-Right, Right-Left, Right-Right).

---

## 💡 Pro Summary for n=3
- **Unlabeled nodes**? ➡ 5 Shapes.
- **Labeled nodes**? ➡ 30 Trees.
- **Maximum height** only? ➡ 4 Skewed Trees.
